<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostReply as ModelsPostReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\PostReply;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) return response(['success' => false, 'errors' => $validator->errors()]);

        $created = $request->only('title', 'discription', 'space_id');
        $created['user_id'] =  auth()->user()->id;

        if ($request->file) {
            $created['file'] = "post_" . $request->spaces_id . "_" . $created['user_id'] . "_" . time() . "." . $request->file->getClientOriginalExtension();
            $request->file->move(public_path('assets/images/posts'), $created['file']);
        }

        $post = Post::create($created)
            ->load(['reactions' => fn ($qry) => $qry->where('user_id', auth()->user()->id), 'replies'])
            ->loadCount('reactions', 'replies');
        if ($post) return response(['success' => true, 'post' => view('includes.post_card', compact('post'))->render(), 'message' => 'Post added successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->load([
            'reactions' => fn ($qry) => $qry->where('user_id', auth()->user()->id),
            'user:id,name',
            'space:id,name',
            'replies.user'
        ])->loadCount('reactions');

        $replies = ModelsPostReply::where('post_id', $post->id)->paginate(5);

        if (request()->ajax()) {
            $repyHtml = "";
            foreach ($replies as $reply)
                $repyHtml .= view('includes.reply_card', compact('reply'))->render();
            return response()->json($repyHtml);
        } else
            return view('posts.show', compact('post', 'replies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return $post;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {

        $update = $request->only('title', 'discription');

        if ($request->has('file')) {

            if (Storage::exists(public_path("images/posts/{$post->file}")))
                Storage::delete(public_path("images/posts/{$post->file}"));
            $update['file'] = "post_" . Auth::id() . "_" . time() . "." . $request->file->extension();
            $request->file->move(public_path('assets/images/posts'), $update['file']);
        }


        $post->update($update);

        $data = Post::find($post->id);
        $data->load([
            'reactions' => fn ($qry) => $qry->where('user_id', auth()->user()->id),
            'user:id,name',
            'space:id,name',
            'replies.user'
        ])->loadCount('reactions');

        return response(view('includes.post_card', ['post' => $data])->render());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->noContent();
    }
}
