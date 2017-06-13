<?php

namespace App\Http\Controllers;

use App\Project;
use App\UserProject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct() {

    }

    /** Get all projects */
    public function index() {
        return Project::all();
    }

    /** Store new project */
    public function store(Request $request) {
        $project =  new Project();
        /** validate the request data */
        $this->validate(Request(),$project->validation, $project->messages);
        /** create new project */
        $project = Project::create(['name' => $request->get('name')]);
        /** create new user_project join */
        UserProject::create(['user_id' =>  Auth::user()->id, 'project_id' => $project->id]);
        /** return success and stored project */
        return ['success' => true, 'message' => 'project has been created', 'project' => $project];
    }

    /** Show project */
    public function show(Project $project) {
        /** If project cant be found return error */
        if(!$project){
            return ['success' => false, 'message' => 'The requested project could not be found'];
        }
        /** return success and requested project */
        return ['success' => true, 'message' => 'project has been found', 'project' => $project];
    }

    /** Update project */
    public function update(Request $request, Project $project) {
        /** If project cant be found return error */
        if(!$project){
            return ['success' => false, 'message' => 'The requested project could not be found'];
        }
        /** validate the request data */
        $this->validate(Request(),$project->validation, $project->messages);
        /** update record */
        $project->name = $request->get('name');
        /** return success and updated project */
        return ['success' => true, 'message' => 'project has been updated', 'project' => $project];
    }

    /** destroy project */
    public function destroy(Project $project) {
        /** If project cant be found return error */
        if(!$project){
            return ['success' => false, 'message' => 'The requested project could not be found'];
        }
        /** delete project */
        $project->delete();
        /** return success message */
        return ['success' => true, 'message' => 'Project '.$project->name.' has been successfully deleted'];
    }
}
