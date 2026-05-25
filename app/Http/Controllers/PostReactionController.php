<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostReaction;
use App\Models\Post;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Reaction;
use App\Models\User;
use App\Models\Space;
use App\Models\SpacesUser;

class PostReactionController extends Controller
{
    function index(Request $request)
    {
        $data = $request->only('post_id', 'reaction');
        $data['user_id'] = auth()->user()->id;
        $oldReaction =  PostReaction::where([['post_id', $request->post_id], ['user_id', auth()->user()->id]])
            ->first();
        $oldReaction ? $oldReaction->update(['reaction' => $request->reaction]) : PostReaction::create($data);
        $post = Post::find($request->post_id);
        $count = PostReaction::where('post_id', '=', $request->post_id)->count();

        if ($post->user_id != auth()->user()->id) {
            $spacePublic = Space::where('id', $post->space_id)->pluck('is_private')->first();
            if (!empty($spacePublic->is_private)) {
                $details = ['post' => $post, 'notify' => 'reacted', 'user' => auth()->user()];
                Notification::send(User::find($post->user_id), new Reaction($details));
            } else {
                $space_user = SpacesUser::where('user_id', '!=', auth()->user()->id)->where('space_id', $post->space_id)->get();
                if (!empty($space_user)) {
                    foreach ($space_user as $spaces) {
                        $post->user_id = $spaces->user_id;
                        $details = ['post' => $post, 'notify' => 'reacted', 'user' => auth()->user()];
                        Notification::send(User::find($post->user_id), new Reaction($details));
                    }
                }
            }
        } else {
            $spacePublic = Space::where('id', $post->space_id)->pluck('is_private')->first();
            if (empty($spacePublic->is_private)) {
                $space_user = SpacesUser::where('user_id', '!=', auth()->user()->id)->where('space_id', $post->space_id)->get();
                if (!empty($space_user)) {
                    foreach ($space_user as $spaces) {
                        $post->user_id = $spaces->user_id;
                        $details = ['post' => $post, 'notify' => 'reacted', 'user' => auth()->user()];
                        Notification::send(User::find($post->user_id), new Reaction($details));
                    }
                }
            }
        }

        return response(['success' => true, 'count' => $count]);
    }
}
