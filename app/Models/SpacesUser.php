<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpacesUser extends Model
{
    use HasFactory;
    public $table = "spaces_user";
	
	protected $fillable = [
	'space_id',
	'user_id',
	'role_id'
    ];

    function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    function spaces()
    {

        return $this->belongsTo(Space::class, 'space_id');
    }


    function posts()
    {
        return $this->hasMany(Post::class, 'space_id', 'space_id');
    }
}
