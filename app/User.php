<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\Welcome;
use phpDocumentor\Reflection\Types\Integer;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $appends = ['avatar_url', 'full_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'handle', 'email', 'password', 'current_team_id'
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
     * Modal validation.
     * @var array
     */
    public $validation = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email|unique:users',
        'handle' => 'required',
        'password' => 'required|confirmed|min:7'
    ];
    /**
     * Modal update validation.
     * @var array
     */
    public $updateValidation = [
        'first_name' => 'required',
        'last_name' => 'required',
        'handle' => 'required'
    ];
    /**
     * Custom validation messages
     * @var array
     */
    public $messages = [
        'first_name.required' => 'Please provide a first name',
        'last_name.required' => 'Please provide a last name',
        'email.required' => 'Please provide an email address',
        'email.email' => 'Please provide a valid email',
        'handle.required' => 'Please provide a handle',
        'password.required' => 'Please provide a password',
        'password.min' => 'Please provide a password that at least 7 charters',
        'password.confirmed' => 'The passwords you entered don\'t appear to match',
    ];


    /**
     * Get user using email
     * @var string $email
     * @return \App\User
     */
    public static function findByEmail($email)
    {
        return static::where(['email' => $email])->first();
    }

    /**
     * Make user from pending user
     * @var Invitation $pendingUser
     * @var array $userDetails
     * @return \App\User
     */
    public static function createFromInvitation(Invitation $invitation, $userDetails)
    {
        /** create new user */
        $user = self::create([
            'first_name' => $userDetails['first_name'],
            'last_name' => $userDetails['last_name'],
            'email' =>  $invitation->email,
            'handle' => $userDetails['handle'],
            'password' => bcrypt($userDetails['password']),
        ]);
        /** add user to team */
        $user->teams()->attach($invitation->team_id);
        /** delete invitation */
        $invitation->delete();
        /** send user welcome email**/
        $user->notify(new Welcome());
        /** return user */
        return $user;
    }

    public function addToTeam(Integer $team_id){
       return  $this->teams()->attach($team_id);
    }
    /**
     * Get users full name.
     */
    public function getFullName(){
        return $this->first_name .' '.$this->last_name;
    }

    /**
     * Get users full name.
     */
    public function getFullNameAttribute() {
        return $this->first_name .' '.$this->last_name;
    }

    /**
     * Get users avatar url.
     */
    public function getAvatarUrlAttribute() {
        if(Storage::exists('app/public/users/'.$this->id) ){
            return asset('storage/users/'.$this->id.'/avatar.png');
        }
        return asset('https://api.adorable.io/avatars/100/'.$this->handle.'@laravel-tasks.png');
    }

    /**
     * Get all user teams.
     */
    public function teams(){
        return $this->belongsToMany(Team::class, 'user_teams');
    }

    /**
     * Get all user tasks.
     */
    public function tasks(){
        return $this->belongsToMany(Task::class,'user_tasks');
    }

    /**
     * Get tasks currently working on.
     */
    public function workingOnIt(){
        return $this->belongsToMany(Task::class,'user_tasks')->where('tasks.status_id', 2);
    }

    /**
     * Get over due tasks.
     */
    public function overDue(){
        $now = Carbon::now()->format('Y-m-d');
        return $this->belongsToMany(Task::class, 'user_tasks')
                    ->where('tasks.due_date', '<', $now)
                    ->where(function($q) {
                        $q->where('tasks.status_id', '!=' ,1)
                          ->orWhereNull('tasks.status_id');
                    }
        );
    }
}
