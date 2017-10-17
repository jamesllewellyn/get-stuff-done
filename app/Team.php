<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\AddedToTeam;
use App\Notifications\InviteUser;
use Auth;

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
     * Get project overview.
     */
    public function getOverview()
    {
        /** create array for overview */
        $teamOverview = [];
        /** get all projects in team */
        $projects = $this->projects()->get();
        /** loop each project and get its overview */
        foreach ($projects as $project) {
            /** add projects overview to team overview */
            $teamOverview[] =  $project->getOverview();
        }
        /** return overview array */
        return $teamOverview;
    }

    /**
     * Invite new user to team
     *
     * @param  string $email
     * @return Invitation
     */
    public function inviteUser(String $email)
    {
        /** check if user already exists */
        $invitedUser = User::findByEmail($email);
        /** create new invitation  */
        $invitation = $this->invitations()->create([
            'user_id' => $invitedUser ? $invitedUser->id : null,
            'email' => $email,
            'team_id' => $this->id,
            'created_by_id' => auth()->user()->id,
            'token' => str_random(24),
        ]);
        /** notify user they have been invited to team */
        $invitation->notify(new InviteUser($this, auth()->user()));
        /** return invitation */
        return $invitation;
    }

    public function isInTeam($email){
        return $this->users()->get()->where('email', $email)->first() ? true : false;
    }
    /**
     * Get all of the pending invitations for the team.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get all Team Projects.
     */
    public function projects()
    {
        return $this->HasMany(Project::class);
    }

    /**
     * Get all Team Users.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_teams');
    }
}
