<?php

namespace App\Http\Controllers;

use App\Team;
use App\Project;
use App\Task;
use Illuminate\Http\Request;
use App\Section;

class SectionController extends Controller
{

    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }

    /**
     * Store new project section
     *
     * @param \Illuminate\Http\Request $request
     * @param Team $team
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Team $team, Project $project) {
        /** authorize user has access to project */
        $this->authorize('access-project', [$team, $project]);
        /** new section model */
        $section =  new Section();
        /** validate the request data */
        $this->validate(Request(),$section->validation, $section->messages);
        /** create new section */
        $section = Section::create(['name' => $request->get('name'), 'project_id' => $project->id]);
        /** return success and stored section */
        return response()->json(['success' => true, 'message' => 'section has been created', 'section' => $section]);
    }

    /**
     * get section data
     *
     * @param Team $team
     * @param Project $project
     * @param Section $section
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team, Project $project, Section $section) {
        /** authorize user has access to section */
        $this->authorize('access-section', [$team, $project, $section]);
        /** return success and requested section */
        return response()->json(['success' => true, 'message' => 'section has been found', 'section' => $section]);
    }

    /**
     * update section data
     *
     * @param \Illuminate\Http\Request $request
     * @param Team $team
     * @param Project $project
     * @param Section $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team, Project $project, Section $section) {
        /** authorize user has access to section */
        $this->authorize('access-section', [$team, $project, $section]);
        /** validate the section data */
        $this->validate(Request(),$section->validation, $section->messages);
        /** update record */
        $section->name = $request->get('name');
        $section->save();
        /** return success and updated project */
        return response()->json(['success' => true, 'message' => 'section has been updated', 'section' => $section]);
    }
    /**
     * Reorder section tasks
     *
     * @param \Illuminate\Http\Request $request
     * @param Team $team
     * @param Project $project
     * @param Section $section
     * @return \Illuminate\Http\Response
     */
    public function reorderTasks(Request $request,Team $team, Project $project, Section $section) {
        /** authorize user has access to section */
        $this->authorize('access-section', [$team, $project, $section]);
        /** Get tasks from request */
        $tasks = $request->get('tasks');
        if(!$tasks){
            return response()->json(['success' => false, 'message' => 'Request did not contain tasks']);
        }
        /** check that user can access task **/
        foreach ($tasks as $task){
            $this->authorize('access-task', [$team, $project, $section, Task::find($task['id'])]);
        }
        /** update task sort order **/
        foreach ($tasks as $t) {
            $task = Task::find($t['id']);
            $task->sort_order = $t['sort_order'];
            $task->save();
        }
        /** return success and updated project */
        return response()->json(['success' => true, 'message' => 'section task have been updated']);
    }

    /**
     * Delete project section
     *
     * @param Team $team
     * @param Project $project
     * @param Section $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team, Project $project, Section $section) {
        /** authorize user has access to section */
        $this->authorize('access-section', [$team, $project, $section]);
        /** delete project */
        $section->delete();
        /** return success message */
        return response()->json(['success' => true, 'message' => 'Section '.$section->name.' has been successfully deleted']);
    }
}
