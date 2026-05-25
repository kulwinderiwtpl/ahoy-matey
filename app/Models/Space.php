<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','cover_img', 'name', 'about', 'is_private', 'is_visible'];
    protected $appends = ['cover_pic'];

    public static $searchable = ['name'];

    function members()
    {
        return $this->hasMany(SpacesUser::class,'space_id');
    }

    function posts()
    {
        return $this->hasMany(Post::class, 'space_id');
    }

    function getCoverPicAttribute()
    {
        return $this->cover_img != "" ? asset("assets/images/space/$this->cover_img") : "";
    }
}
