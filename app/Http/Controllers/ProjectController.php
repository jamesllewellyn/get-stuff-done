<?php

namespace App\Http\Controllers;

use App\Project;
use App\UserProject;
use Auth;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }
    /**
     * Get all projects.
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return Project::all();
    }
    /**
     * Store new project
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * get project data
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project) {
        /** If project cant be found return error */
        if(!$project){
            return ['success' => false, 'message' => 'The requested project could not be found'];
        }
        /** return success and requested project */
        return ['success' => true, 'message' => 'project has been found', 'project' => $project];
    }

    /**
     * Get all project sections
     * @param \Illuminate\Http\Request $request
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function sections(Request $request, Project $project) {
        /** If project cant be found return error */
        if(!$project){
            return ['success' => false, 'message' => 'The requested project could not be found'];
        }
        /** return project sections */
        return $project->sections()->get();
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
     * Delete project
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
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
