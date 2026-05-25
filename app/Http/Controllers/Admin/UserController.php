<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\SpacesUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Space;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = new User;
        $users = $user->getUserLists();
    }

    public function edit(Request $request)
    {
        $user = new User;
        $userId = $user->getSpecificUser($request->id)->toArray();

        return response()->json($userId);
    }

    public function update(Request $request)
    {

	
        $user = new User;
        if ($request->file('profile_pic') && $request->file('cover_image')) {
            $file = $request->file('profile_pic');
            $fileName = $file->getClientOriginalName();

            $file->move(public_path("assets/images/users"), $fileName);

            $cfile = $request->file('cover_image');
            $cfileName = "cover_" . $request->post('id') . "_" . time() . "." . $cfile->getClientOriginalName();

            $cfile->move(public_path('assets/images/users/covers'), $cfileName);
			
			$data = array('cover_pic' => $cfile, 'cover_img' => $cfileName, 'profile_pic' => $file, 'profile_img' => $fileName, 'name' => $request->post('name'), 'email' => $request->post('email'), 'tagline' => $request->post('tagline'), 'phone_number' => $request->post('phone_number'));
			$data = array_push($data,$pass);
            $userId = $user->getSpecificUser($request->id)->update($data);
			
            if ($userId) 
			{
                return response()->json(['success' => 'true']);
            } 
			else 
			{
                return response()->json(['success' => 'false']);
            }
			
        } 
		else if ($request->file('profile_pic') && !$request->file('cover_image')) 
		{
            $file = $request->file('profile_pic');
            $fileName = $file->getClientOriginalName();

            $file->move(public_path("assets/images/users"), $fileName);

			$data = array('profile_pic' => $file, 'profile_img' => $fileName, 'name' => $request->post('name'), 'email' => $request->post('email'), 'tagline' => $request->post('tagline'), 'phone_number' => $request->post('phone_number'));
			$data = array_push($data,$pass);
            $userId = $user->getSpecificUser($request->id)->update($data);
			
            if ($userId) 
			{
                return response()->json(['success' => 'true']);
            } 
			else 
			{
                return response()->json(['success' => 'false']);
            }
        } 
		elseif (!$request->file('profile_pic') && $request->file('cover_image')) 
		{
            $cfile = $request->file('cover_image');
            $cfileName = "cover_" . $request->post('id') . "_" . time() . "." . $cfile->getClientOriginalName();

            $cfile->move(public_path('assets/images/users/covers'), $cfileName);
			
			$data=array('cover_pic' => $cfile, 'cover_img' => $cfileName, 'name' => $request->post('name'), 'email' => $request->post('email'), 'tagline' => $request->post('tagline'), 'phone_number' => $request->post('phone_number'));
			$data = array_push($data,$pass);
            $userId = $user->getSpecificUser($request->id)->update($data);
			
            if ($userId) {
                return response()->json(['success' => 'true']);
            } else {
                return response()->json(['success' => 'false']);
            }
			
        } else {
		if($request->new_password){
			$data=array('name' => $request->post('name'), 'email' => $request->post('email'), 'tagline' => $request->post('tagline'), 'phone_number' => $request->post('phone_number'),'password'=>Hash::make($request->password1));			
		}else{			
			$data=array('name' => $request->post('name'), 'email' => $request->post('email'), 'tagline' => $request->post('tagline'), 'phone_number' => $request->post('phone_number'));
		}

            $userId = $user->getSpecificUser($request->id)->update($data);
			
            if ($userId) {
                return response()->json(['success' => 'true']);
            } else {
                return response()->json(['success' => 'false']);
            }
			
        }
    }

    /*
	------------------------------
	======== Delete User =========
	------------------------------
	*/
    public function delete(Request $request)
    {
        $user = new User;
        $userId = $user->getSpecificUser($request->id)->delete();
        if ($userId) {
            return response()->json(['success' => 'true']);
        } else {
            return response()->json(['success' => 'false']);
        }
    }

    public function viewPosts(Request $request)
    {
        $posts = Post::where('user_id', '=', $request->id)
            ->with([
                'reactions' => fn ($qry) => $qry->where('user_id', $request->id),
                'user:id,name',
                'space:id,name',
            ])
            ->withCount('reactions', 'replies')
            ->orderBy('id', 'DESC')
            ->paginate(15);

        $spacesUsers = SpacesUser::where('user_id', '=', $request->id)->with('spaces', 'role')->get();
        $spaces = Space::where('user_id', '=', $request->id)->get();
        $user = new User;
        $userId = $user->getSpecificUser($request->id);
        $roleType = Role::all();
        if (request()->ajax()) {

            $html = "";
            foreach ($posts as $post) {
                $html .= "<div class='row justify-content-center'><div class='col-lg-8'>";
                $html .=  view('includes.post_card', compact('post'))->render();
                $html .= "</div></div>";
            }

            return response()->json($html);
        } else {
            return view('admin.user.profile', compact('posts', 'spacesUsers', 'userId', 'spaces', 'roleType'));
        }
    }

    public function storePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) return response(['success' => false, 'errors' => $validator->errors()]);

        $created = $request->only('title', 'discription', 'space_id');
        $created['user_id'] =  $request->user_id;

        if ($request->file) {
            $created['file'] = "post_" . $request->spaces_id . "_" . $created['user_id'] . "_" . time() . "." . $request->file->getClientOriginalExtension();
            $request->file->move(public_path('assets/images/posts'), $created['file']);
        }

        $post = Post::create($created)
            ->load(['reactions' => fn ($qry) => $qry->where('user_id', $request->user_id), 'replies'])
            ->loadCount('reactions', 'replies');
        if ($post) return response(['success' => true, 'post' => view('includes.post_card', compact('post'))->render(), 'message' => 'Post addes successfully', 'post_id' => $post->id]);
    }

    /*
	----------------------------------
	========== Delete Post ===========
	----------------------------------
	*/

    public function destroy(Request $request)
    {
        $post = Post::find($request->post_id);
        $post->delete();
        return response()->noContent();
    }

    /*
	----------------------------------
	========== Delete Post ===========
	----------------------------------
	*/

    public function leaveSpace(Request $request)
    {
        SpacesUser::where('space_id',$request->space_id)->where('user_id',$request->user_id)->delete();
        return response()->noContent();
    }
	
    /*
	----------------------------------
	========== Update Post ===========
	----------------------------------
	*/

    public function updatePost(Request $request, Post $post)
    {
        $update = $request->only('title', 'discription');
        $post = Post::find($request->post_id);
        if ($request->has('file')) {
            if (Storage::exists(public_path("images/posts/{$post->file}")))
                Storage::delete(public_path("images/posts/{$post->file}"));
            $update['file'] = "post_" . $request->post_id . "_" . time() . "." . $request->file->extension();
            $request->file->move(public_path('assets/images/posts'), $update['file']);
        }
        Post::where('id', $request->post_id)->update($update);

        $post = Post::find($request->post_id);
        $post->load([
            'reactions' => fn ($qry) => $qry->where('user_id', $request->user_id),
            'user:id,name',
            'space:id,name',
            'replies.user'
        ])->loadCount('reactions');
        return response(view('admin.includes.post_card', ['post' => $post])->render());
    }

    /*
	--------------------------------
	========== Edit Post ===========
	--------------------------------
	*/

    public function editPost(Request $request)
    {
        $data = Post::find($request->post);
        return $data;
    }

    /*
	-----------------------------------------
	========== Change Cover Picture =========
	-----------------------------------------
	*/

    function changeCover(Request $request, Post $post)
    {
        $post = Post::find($request->post_id);
        if ($request->has('profile_cover')) {
            if (Storage::exists(public_path("images/posts/{$post->file}")))
                Storage::delete(public_path("images/posts/{$post->file}"));
            $update['file'] = "post_" . Auth::id() . "_" . time() . "." . $request->profile_cover->extension();
            $request->profile_cover->move(public_path('assets/images/posts'), $update['file']);
            $post->update($update);
            return response(['message' => 'Cover picture is updated!', 'cover' => asset("assets/images/posts/" . $update['file'] . "")]);
        }
    }

    /*
	----------------------------------
	========== Delete Spaces =========
	----------------------------------
	*/

    public function destroySpaces(Request $request)
    {
        $spec = Space::where('id', $request->spaces_id)->first();
        SpacesUser::where('space_id', $spec->id)->delete();
        $spec->delete();
        return response()->noContent();
    }
}
