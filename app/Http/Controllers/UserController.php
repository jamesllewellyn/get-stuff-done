<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class UserController extends Controller
{
    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }
    /**
     * Store new user
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
    }

    /**
     * get logged in user data
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        /** return current logged in user */
        return $request->user();
    }

    /**
     * Update project
     * @param \Illuminate\Http\Request $request
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project) {
        /** If project cant be found return error */
        if(!$project){
            return ['success' => false, 'message' => 'The requested project could not be found'];
        }
        /** validate the request data */
        $this->validate(Request(),$project->validation, $project->messages);
        /** update record */
        $project->name = $request->get('name');
        $project->save();
        /** return success and updated project */
        return ['success' => true, 'message' => 'project has been updated', 'project' => $project];
    }

    /**
     * Delete user
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        /** If user cant be found return error */
        if(!$user){
            return ['success' => false, 'message' => 'The requested user could not be found'];
        }
        /** delete user */
        $user->delete();
        /** return success message */
        return ['success' => true, 'message' => 'User '.$user->name.' has been successfully deleted'];
    }

    /**
     * Get users projects
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function projects(User $user) {
        /** If user cant be found return error */
        if(!$user){
            return ['success' => false, 'message' => 'The requested user could not be found'];
        }
        /** return success message */
        return $user->projects()->with('sections', 'sections.tasks')->get();
    }
}
