<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'teams';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Modal validation.
     * @var array
     */
    public $validation = [
        'name' => 'required',
    ];

    /**
     * Custom validation messages
     * @var array
     */
    public $messages = [
        'name.required' => 'Please provide a name for your team'
    ];

    /**
     * Get all Team Projects.
     */
    public function projects(){
        return $this->HasMany(Project::class);
    }
    /**
     * Get all Team Users.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_teams');
    }

    /**
     * Get all UserTeam.
     */
    public function userTeams(){
        return $this->HasMany(UserTeam::class);
    }


}
