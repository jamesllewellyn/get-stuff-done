<?php

namespace App\Http\Controllers;

use App\User;
use App\UserTeam;
use Illuminate\Http\Request;
use App\Team;
use Auth;
use App\PendingUser;
use App\Notifications\AddedToTeam;
use App\Notifications\InviteUser;

class TeamController extends Controller
{
    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }
    /**
     * Store a new Team
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $team =  new Team();
        /** validate the request data */
        $this->validate(Request(),$team->validation, $team->messages);
        /** create team */
        $team->name = $request->name;
        $team->save();
        /** add user to team */
        $userTeam = UserTeam::create(['user_id' => $user->id, 'team_id' => $team->id]);
        /** check team has been created */
        if(!$userTeam){
            return response()->json(['success' => false, 'message' => 'User could not be added to team']);
        }
        return response()->json(['success' => true, 'team' => $team]);
    }

    /**
     * Get all team projects.
     *
     * @param  \App\Team $team
     * @return \Illuminate\Http\Response
     */
    public function projects(Team $team)
    {
        /** authorize user has access to project */
        $this->authorize('access-team', $team);
        /** get all teams projects */
        $projects = $team->projects()->get();
        /** return response */
        return response()->json($projects);
    }

    /**
     * Get overview of projects data
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function overview(Team $team)
    {
        /** create array from overview */
        $teamOverview = [];
        /** get all project in team */
        $projects = $team->projects()->get();
        /** loop each project and get its overview */
        foreach ($projects as $project) {
            /** add projects overview to team overview */
            $teamOverview[] =  $project->getOverview();
        }
        /** return response */
        return response()->json(['success' => true, 'overview' =>$teamOverview]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        /** authorize user has access to project */
        $this->authorize('access-team', $team);
        /** validate the request data */
        $this->validate(Request(), $team->validation, $team->messages);
        /** update team */
        $team->name = $request->name;
        $team->save();
        /** return response */
        return response()->json(['success' => true, 'team' => $team]);
    }

    /**
     * Add User to team.
     * @param  \Illuminate\Http\Request  $request
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request, Team $team)
    {
        /** authorize user has access to team */
        $this->authorize('access-team', $team);
        /** check if user already exists */
        $user = User::where('email',$request->email)->first();
        /** check if user already exists */
        if($user){
            /** if user exists add user to team **/
            return $this->existingUser($team, $user, $request->user());
        }
        /** invite new user and add to pending users table **/
        return $this->pendingUser($request, $team,$request->user());
    }

    /**
     * Private Functions
     *
     */

    /**
     * Store pending user
     *
     * @param Team $team
     * @param User $user
     * @param User $addedBy
     * @return \Illuminate\Http\Response
     */
    private function existingUser(Team $team, User $user, User $addedBy){
        /** check if user is already member of the team */
        $teams = $user->teams()->get()->pluck('id');
        /** if user is a member return error */
        if(in_array($team->id, $teams->toArray())){
            return response()->json(['success' => false, 'message' => $user->full_name.' is already a member of team '.$team->name]);
        }
        /** create new user team */
        $userTeam = new UserTeam();
        $userTeam->user_id = $user->id;
        $userTeam->team_id = $team->id;
        $userTeam->save();
        /** notify user that they have been added to a new team **/
        $user->notify(new AddedToTeam($team, $addedBy));
        /** return successful response **/
        return response()->json(['success' => true, 'message' => $user->full_name.' has been added to team '.$team->name, 'user' => $user]);
    }

    /**
     * Store pending user
     *
     * @param  \Illuminate\Http\Request $request
     * @param Team $team
     * @param User $addedBy
     * @return \Illuminate\Http\Response
     */
    private function pendingUser(Request $request, Team $team, User $addedBy) {
        /** create new pending user */
        $pending = new PendingUser();
        /** validate the request data */
        $this->validate(Request(),$pending->validation, $pending->messages);
        /** add user to pending table*/
        $pending->email = $request->email;
        $pending->team_id = $team->id;
        $pending->created_by_id = $addedBy->id;
        $pending->token = str_random(24);
        $pending->save();
        $pending->notify(new InviteUser($team, $addedBy));
        /** return success message */
        return response()->json(['success' => true, 'message' => $pending->email.' has been invited to team']);
    }

}
