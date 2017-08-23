<?php

namespace App\Http\Controllers;

use App\Project;
use App\Team;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ProjectController extends Controller
{
    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }

    /**
     * Check user can access project
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Team $TEAM
     * @param \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function canAccess(Request $request, Team $team, Project $project){
        /** authorize user is can access project */
        $this->authorize('access-project', [$team, $project]);
        /** return success */
        return response()->json(['success' => true, 'message' => 'user can access project', 'project' => $project]);
    }

    /**
     * Store new project
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Team $team
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Team $team) {
        /** authorize user is in team */
        $this->authorize('access-team', $team);
        $project = new Project();
        /** validate the request data */
        $this->validate(Request(),$project->validation, $project->messages);
        /** create new project */
        $project = Project::create(['name' => $request->get('name'), 'team_id' => $team->id]);
        /** get user  */
        $loggedBy =  Auth::user();
        /** get team members where are not currently logged in user*/
        $users = $team->users()->where('users.id', '<>', $loggedBy->id )->get();
        /** send notifications  */
        Notification::send($users, new \App\Notifications\ProjectAdded($team, $project, $loggedBy) );
        /** broadcast added project event */
        broadcast(new \App\Events\ProjectAdded($team->id, $project))->toOthers();
        /** return success and stored project */
        return response()->json(['success' => true, 'message' => 'project has been created', 'project' => $project]);
    }

    /**
     * get project data
     *
     * @param \App\Team $team
     * @param \App\Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team, Project $project) {
        /** authorize user has access to project */
        $this->authorize('access-project', [$team, $project]);
        /** get project with all sections and tasks */
        $project = Project::where('id', $project->id)->with('sections', 'sections.tasks', 'sections.tasks.assignedUsers')->first();
        /** return success and requested project */
        return response()->json(['success' => true, 'message' => 'project has been found', 'project' => $project]);
    }

    /**
     * Get all project sections
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function sections(Team $team, Project $project) {
        /** If project cant be found return error */
        if(!$project){
            return response()->json(['success' => false, 'message' => 'The requested project could not be found']);
        }
        $this->authorize('access-project', [$team, $project]);
        /** return project sections */
        return response()->json($project->sections()->get());
    }

    /**
     * Update project
     *
     * @param \Illuminate\Http\Request $request
     * @param Team $team
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team, Project $project) {
        /** If project cant be found return error */
        if(!$project){
            return response()->json(['success' => false, 'message' => 'The requested project could not be found']);
        }
        /** authorize user has access to project */
        $this->authorize('access-project', [$team, $project]);
        /** validate the request data */
        $this->validate(Request(),$project->validation, $project->messages);
        /** update record */
        $project->name = $request->get('name');
        $project->save();
        /** return success and updated project */
        return response()->json(['success' => true, 'message' => 'project has been updated', 'project' => $project]);
    }

    /**
     * Delete project
     *
     * @param Team $team
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team, Project $project) {
        /** If project cant be found return error */
        if(!$project){
            return response()->json(['success' => false, 'message' => 'The requested project could not be found']);
        }
        /** authorize user has access to project */
        $this->authorize('access-project', [$team, $project]);
        /** delete project */
        $project->delete();
        /** return success message */
        return response()->json(['success' => true, 'message' => 'Project '.$project->name.' has been successfully deleted']);
    }
}
