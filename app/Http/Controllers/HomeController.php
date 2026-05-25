<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\SpacesUser;
use App\Models\User;
use App\Models\Space;

class HomeController extends Controller
{


    public function index()
    {
        $spaceIds = [];
        $spaceAll =  Space::where('user_id', '=', auth()->user()->id)->withCount('members')->get();
		$m=0;
        foreach ($spaceAll as $spaces){
			//$spaces->members=$m;
			$m++;
		}
		
        $spaces =  SpacesUser::where('user_id', '=', auth()->user()->id)->select('space_id')->get();


        foreach ($spaces as $space)
            $spaceIds[] = $space->space_id;

        $posts = Post::whereIn('space_id', $spaceIds)
            ->with([
                'reactions' => fn ($qry) => $qry->where('user_id', auth()->user()->id),
                'user:id,name',
                'space:id,name',
            ])
            ->withCount('reactions', 'replies')->paginate(2);

        if (request()->ajax()) {
            $html = "";
            foreach ($posts as $post) {
                $html .= "<div class='row justify-content-center'><div class='col-md-9'>";
                $html .=  view('includes.post_card', compact('post'))->render();
                $html .= "</div></div>";
            }

            return response()->json($html);
        } else
            return view('home', compact('posts', 'spaceAll'));
    }

    function explore()
    {
        $spaceIds = [];
        $spaces =  SpacesUser::where('user_id', '=', auth()->user()->id)->select('space_id')->get();


        foreach ($spaces as $space)
            $spaceIds[] = $space->space_id;

        $posts = Post::whereIn('space_id', $spaceIds)
            ->with([
                'reactions' => fn ($qry) => $qry->where('user_id', auth()->user()->id),
                'user:id,name',
                'space:id,name',
            ])
            ->withCount('reactions', 'replies')->latest()->take(5)->get();

        return view('explore', compact('posts'));
    }

    function search($search)
    {
        $searchData = [];
        $html = "";
        foreach (['Post', 'User', 'Space'] as $name)
            if ($this->modalSearch($name, $search) != null) $searchData[$name] = $this->modalSearch($name, $search);

        return response()->json($searchData);
    }

    function modalSearch($name, $search)
    {
        $model = 'App\Models\\' . $name;
        $qry = $model::query();
        $fields = $model::$searchable;

        foreach ($fields as $field)
            $qry->orWhere($field, 'LIKE', "%$search%");

        $data = $qry->take(10)->get();
        return $data->count() > 0 ? $data : null;
    }
}
