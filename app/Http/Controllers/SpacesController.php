<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Space;
use App\Models\Post;
use App\Models\SpacesUser;
use Illuminate\Support\Facades\Validator;

class SpacesController extends Controller
{
    function showSpaces($id)
    {
        $space = Space::find($id)->load(['members', 'members.user', 'members.role']);

        $posts = Post::where('space_id', "=", $id)
            ->with(['reactions' => fn ($qry) => $qry->where('user_id', auth()->user()->id), 'replies'])
            ->withCount('reactions', 'replies')->paginate(10);

        return view('spaces.show', compact('space', 'posts'));
    }

    function changeCover(Request $request)
    {
        $spaces = Space::find($request->id);
        if ($spaces->cover_img != "") unlink(public_path("assets/images/space/" . $spaces->cover_img));
        $newCover = "cover_" . auth()->user()->id . "_" . time() . "." . $request->cover_img->getClientOriginalExtension();
        $request->cover_img->move(public_path('assets/images/space'), $newCover);

        $user = $spaces->update(['cover_img' => $newCover]);
        return response(['message' => 'Cover is updated', 'pic' => asset("assets/images/space/$spaces->cover_img")]);
    }

    function leaveSpace($id)
    {
        $space  = SpacesUser::find($id)->delete();
        return response()->noContent();
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'space_title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) return response(['success' => false, 'errors' => $validator->errors()]);

        $created = $request->only('space_title', 'description', 'user_id');
        $created['user_id'] = auth()->user()->id;
        $created['name'] =  $request->space_title;
        $created['about'] =  $request->description;
        if ($request->is_private && $request->is_private == 'on') {
            $created['is_private'] =  1;
        } else {
            $created['is_private'] =  0;
        }

        if ($request->is_visible && $request->is_visible == 'on') {
            $created['is_visible'] =  0;
        } else {
            $created['is_visible'] =  1;
        }
        $spaces = Space::create($created);
        SpacesUser::create(['space_id' => $spaces->id, 'user_id' => auth()->user()->id, 'role_id' => 1]);

        if ($spaces) return response(['success' => true, 'url' => route('show-spaces', ['id' => $spaces->id]), 'message' => 'Space added successfully', 'space_id' => $spaces->space_id]);
    }
}
