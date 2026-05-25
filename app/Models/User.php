<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $appends = ['profile_pic', 'cover_pic'];
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tagline',
        'profile_img',
        'cover_img'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static $searchable = ['name', 'email'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function getProfilePicAttribute()
    {
        return $this->profile_img != "" ? asset("assets/images/users/$this->profile_img") : asset("assets/images/user.png");
    }

    function getCoverPicAttribute()
    {
        return $this->cover_img != "" ? asset("assets/images/users/covers/$this->cover_img") : "";
    }

    function spaces()
    {
        return $this->belongsToMany(Spaces::class);
    }

    public function getUserLists()
    {
        return $this->orderBy('id', 'DESC')->get();
    }

    public function getSpecificUser($id)
    {
        return $this->where('id', $id)->first();
    }
}
