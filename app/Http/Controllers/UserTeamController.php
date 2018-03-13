<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Team;

class UserTeamController extends Controller
{
    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api', ['except' => ['invite']]);
    }

    /**
     * Get users teams
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** return success message */
        return response()->json($user->teams()->with(['projects','users'])->get());
    }

    /**
     * Update users current team
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** validate the request data */
        $this->validate(Request(),['teamId' => 'required']);
        /** get team */
        $team = Team::find($request->teamId);
        /** authorize user belongs to team */
        $this->authorize('access-team',$team);
        /** update record */
        $user->current_team_id = $team->id;
        $user->save();
        /** return success and updated project */
        return response()->json(['success' => true, 'message' => 'Team has been switched', 'user' => $user]);
    }
}
