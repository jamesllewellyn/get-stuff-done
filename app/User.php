<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $appends = ['avatar_url'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get all user projects.
     */
    public function projects(){
        return $this->hasManyThrough('App\Project', 'App\UserProject', 'user_id' ,'id' );
    }
    /**
     * Get users avatar url.
     */
    public function getAvatarUrlAttribute() {
        if(Storage::exists('app/users/'.$this->id.'/avatar.png') ){
            return asset('storage/users/'.$this->id.'/avatar.png');
        }
        return asset('storage/users/default-avatar.png');
    }
}
