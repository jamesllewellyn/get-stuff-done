<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;
use App\User;
use Hash;
use Auth;

class UserController extends Controller
{
    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api', ['except' => ['invite']]);
    }

    /**
     * get logged in user data
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        /** return current logged in user */
        return response()->json($request->user());
    }

    /**
     * Update project
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
       /** validate the request data */
        $this->validate(Request(),$user->updateValidation, $user->messages);
        /** update record */
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->handle = $request->handle;
        $user->save();
        /** return success and updated project */
        return response()->json(['success' => true, 'message' => 'user has been updated', 'user' => $user]);
    }
    /**
     * Update current team
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function updateTeam(Request $request, User $user) {
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

    /**
     * Delete user
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** delete user */
        $user->delete();
        /** return success message */
        return response()->json(['success' => true, 'message' => 'User '.$user->getFullName().' has been successfully deleted']);
    }

    /**
     * Get users teams
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function getTeams(User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** return success message */
        return response()->json($user->teams()->with(['projects','users'])->get());
    }

    /**
     * all users tasks
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function getTasks(User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** get tasks user is currently working on */
        $tasks = $user->tasks()->with('section', 'section.project', 'section.project.team')->get();
        /** return success message */
        return response()->json($tasks);
    }

    /**
     * Get tasks user is currently working on
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function getWorkingOnIt(User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** get tasks flagged as working on it */
        $tasks = $user->workingOnIt()->with('section', 'section.project', 'section.project.team')->get();
        /** return success message */
        return response()->json($tasks);
    }

    /**
     * Get tasks that are over due
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function getOverDue(User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** get users over due tasks */
        $tasks = $user->overDue()->with('section', 'section.project', 'section.project.team')->get();
        /** return over due tasks */
        return response()->json($tasks);
    }

    /**
     * Get users unread notifications
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function getNotifications(User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** return unread notifications */
        return response()->json($user->unreadNotifications);
    }

    /**
     * Mark all user notifications as read
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function clearNotifications(User $user) {
        /** authorize user */
        $this->authorize('access-user', $user);
        /** mark all user notifications as read **/
        $user->unreadNotifications->markAsRead();
        /** return success */
        return response()->json(['success' => 'true', 'message' => 'All notifications marked as read']);
    }

}
