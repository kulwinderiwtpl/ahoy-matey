<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Spaces;
use App\Models\SpacesUser;

class UserController extends Controller
{
    function setting()
    {
        return view('user.setting');
    }

    function updateSetting(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'tagline' => 'required'
        ]);

        $update = $request->only('name', 'tagline');

        if ($request->profile_pic) {
            if (auth()->user()->profile_img != "") unlink(public_path("assets/images/users/" . auth()->user()->profile_img));
            $update['profile_img'] = auth()->user()->id . "_" . time() . "." . $request->profile_pic->getClientOriginalExtension();
            $request->profile_pic->move(public_path("assets/images/users"), $update['profile_img']);
        }

        $user = User::find(auth()->user()->id)->update($update);
        return redirect()->route('home')->with('success', 'profile is updated successfully');
    }

    function profile()
    {
        $posts = Post::where('user_id', '=', auth()->user()->id)
            ->with([
                'reactions' => fn ($qry) => $qry->where('user_id', auth()->user()->id),
                'user:id,name',
                'space:id,name',
            ])
            ->withCount('reactions', 'replies')
            ->paginate(2);
        $spacesUsers = SpacesUser::where('user_id', '=', auth()->user()->id)->with('spaces', 'role')->get();


        if (request()->ajax()) {

            $html = "";
            foreach ($posts as $post) {
                $html .= "<div class='row justify-content-center'><div class='col-lg-8'>";
                $html .=  view('includes.post_card', compact('post'))->render();
                $html .= "</div></div>";
            }

            return response()->json($html);
        } else
            return view('user.profile', compact('posts', 'spacesUsers'));
    }

    function changeCover(Request $request)
    {
        if (auth()->user()->cover_img != "") unlink(public_path("assets/images/users/covers/" . auth()->user()->cover_img));
        $newCover = "cover_" . auth()->user()->id . "_" . time() . "." . $request->profile_cover->getClientOriginalExtension();
        $request->profile_cover->move(public_path('assets/images/users/covers'), $newCover);

        $user = User::find(auth()->user()->id)->update(['cover_img' => $newCover]);
        return response(['message' => 'Profile picture is updated!', 'pic' => asset("assets/images/users/covers/$newCover")]);
    }

    function notifications()
    {
        $notifications = auth()->user()->notifications()->paginate(2);
        if (request()->ajax()) {
            $html = "";
            foreach ($notifications as $notification)
                $html .= view('user.notification_card', compact('notification'))->render();
            return response()->json($html);
        } else
            return view('user.notification', compact('notifications'));
    }

    function readNotifications(Request $request)
    {
        $notification = auth()->user()->unreadNotifications;

        if ($request->id  == "all")
            $notification->markAsRead();
        else
            $notification->when($request->id, fn ($qry) => $qry->where('id', $request->id))->markAsRead();

        return response(['success' => true, 'count' => auth()->user()->unreadNotifications()->count()]);
    }

    function member($id)
    {
        $user = User::find($id);

        $posts = Post::where('user_id', '=', $id)
            ->with([
                'reactions' => fn ($qry) => $qry->where('user_id', auth()->user()->id),
                'user:id,name',
                'space:id,name',
            ])
            ->withCount('reactions', 'replies')
            ->paginate(2);
        $spacesUsers = SpacesUser::where('user_id', '=', $id)->with('spaces', 'role')->get();
        return view('member.index', compact('posts', 'spacesUsers', 'user'));
    }
}
