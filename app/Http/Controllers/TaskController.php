<?php

namespace App\Http\Controllers;

use App\Section;
use App\Task;
use App\SectionTask;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }

    /**
     * Store new task
     * @param \Illuminate\Http\Request $request
     * @param Project $project
     * @param Section $section
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project, Section $section) {
        $task =  new Task();
        /** validate the request data */
        $this->validate(Request(),$task->validation, $task->messages);
        /** create new task and join*/
        $task = Task::create(['name' => $request->get('name'), 'due_date' => Carbon::parse($request->get('due_date')),'due_time' => $request->get('due_time')]);
        SectionTask::create(['section_id' => $section->id, 'task_id' => $task->id]);
        /** return success and stored task */
        return ['success' => true, 'message' => 'task has been created', 'task' => $task];
    }

    /**
     * get task data
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task) {
        /** If task cant be found return error */
        if(!$task){
            return ['success' => false, 'message' => 'The requested task could not be found'];
        }
        /** return success and requested task */
        return ['success' => true, 'message' => 'task has been found', 'task' => $task];
    }

    /**
     * update task data
     * @param \Illuminate\Http\Request $request
     * @param Section $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task) {
        /** If section cant be found return error */
        if(!$task){
            return ['success' => false, 'message' => 'The requested task could not be found'];
        }
        /** validate the task data */
        $this->validate(Request(),$task->validation, $task->messages);
        /** update record */
        $task->name = $request->get('name');
        $task->due_date =  Carbon::parse($request->get('due_date'));
        $task->save();
        /** return success and updated task */
        return ['success' => true, 'message' => 'task has been updated', 'task' => $task];
    }

    /**
     * Delete task
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task) {
        /** If task cant be found return error */
        if(!$task){
            return ['success' => false, 'message' => 'The requested task could not be found'];
        }
        /** delete project */
        $task->delete();
        /** return success message */
        return ['success' => true, 'message' => 'Task '.$task->name.' has been successfully deleted'];
    }
}
