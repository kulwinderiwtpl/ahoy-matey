<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'space_id', 'title', 'discription', 'file'];
    protected $appends = ['file_path'];

    public static $searchable = ['title'];

    function getFilePathAttribute()
    {
        return asset("assets/images/posts/$this->file");
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function reactions()
    {
        return $this->hasMany(PostReaction::class);
    }

    function replies()
    {
        return $this->hasMany(PostReply::class);
    }

    function space()
    {
        return $this->hasOne(Space::class, 'id', 'space_id');
    }
}
