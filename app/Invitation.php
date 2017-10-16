<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Invitation extends Model
{
    use Notifiable;
    protected $table = 'invitations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id', 'user_id', 'token', 'email', 'created_by_id'
    ];

    /**
     * Modal validation.
     * @var array
     */
    public $validation = [
        'email' => 'required|email|unique_with:invitations,email'
    ];

    /**
     * Custom validation messages
     * @var array
     */
    public $messages = [
        'email.unique' => 'User already exists',
        'email.email' => 'That email address doesn\'t look quiet right',
        'email.unique_with' => 'Looks like this user has already been invited to this team',
    ];

    /**
     * Get pending user using email and token
     * @var string $email
     * @var string $token
     * @return \App\Invitation
     */
    public static function findByEmailAndToken($email, $token)
    {
        return static::where(['email' => $email, 'token' => $token])->with('team')->first();
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }
}
