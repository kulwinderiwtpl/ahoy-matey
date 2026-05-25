<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\SpacesUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Space;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpacesController extends Controller
{
	/*
	------------------------
	===== Store Spaces =====
	------------------------	
	*/

	function storeSpaces(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'space_title' => 'required',
		]);

		if ($validator->fails()) return response(['success' => false, 'errors' => $validator->errors()]);

		$created = $request->only('space_title', 'description', 'user_id');
		$created['user_id'] = $request->user_id;
		$created['name'] =  $request->space_title;
		$created['about'] =  $request->description;
		if ($request->is_private && $request->is_private == 'on') {
			$created['is_private'] =  1;
		}

		if ($request->is_visible && $request->is_visible == 'on') {
			$created['is_visible'] =  0;
		} else {
			$created['is_visible'] =  1;
		}
		if ($request->space_id > 0) {
			$post = Space::find($request->space_id);
			$post->update($created);
		} else {
			$post = Space::create($created);
			SpacesUser::create(['space_id' => $post->id, 'user_id' => $request->user_id, 'role_id' => $request->role_id]);
		}

		if ($post) return response(['success' => true, 'post' => view('admin/user/show', compact('post'))->render(), 'message' => 'Space added successfully', 'post_id' => $post->id, 'space_id' => $request->space_id]);
	}

	function showSpaces(Request $request)
	{
		// $space = Space::find('id',$request->post)->first();
		$space = Space::find($request->post)->load(['members', 'members.user', 'members.role']);
		if ($space) {
			return response(['success' => true, 'post' => $space]);
		} else {
			return response(['success' => false, 'post' => '']);
		}
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
}
