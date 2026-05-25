<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostReply;
use Illuminate\Http\Request;
use App\Notifications\PostReply as PostReplyNotification;
use App\Models\User;
use App\Models\Space;
use App\Models\SpacesUser;
use Illuminate\Support\Facades\Notification;

class PostReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $create = PostReply::create($request->only('user_id', 'post_id', 'reply'));
        $html =  view('includes.reply_card', ['reply' => $create->load('user')])->render();

        $post = Post::find($request->post_id);

        if ($post->user_id != auth()->user()->id) {
            $spacePublic = Space::where('id', $post->space_id)->pluck('is_private')->first();
            if (!empty($spacePublic->is_private)) {
                $details = ['post' => $post, 'notify' => 'commented', 'user' => auth()->user()];
                Notification::send(User::find($post->user_id), new PostReplyNotification($details));
            } else {
                $space_user = SpacesUser::where('user_id', '!=', auth()->user()->id)->where('space_id', $post->space_id)->get();
                if (!empty($space_user)) {
                    foreach ($space_user as $spaces) {
                        $post->user_id = $spaces->user_id;
                        $details = ['post' => $post, 'notify' => 'commented', 'user' => auth()->user()];
                        Notification::send(User::find($post->user_id), new PostReplyNotification($details));
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
                        $details = ['post' => $post, 'notify' => 'commented', 'user' => auth()->user()];
                        Notification::send(User::find($post->user_id), new PostReplyNotification($details));
                    }
                }
            }
        }
        return response()->json(['html' => $html]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PostReply  $postReply
     * @return \Illuminate\Http\Response
     */
    public function show(PostReply $postReply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PostReply  $postReply
     * @return \Illuminate\Http\Response
     */
    public function edit(PostReply $postReply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PostReply  $postReply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostReply $postReply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PostReply  $postReply
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostReply $postReply)
    {
        //
    }
}
