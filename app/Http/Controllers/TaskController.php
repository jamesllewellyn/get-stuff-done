<?php

namespace App\Http\Controllers;

use App\Notifications\UserAssignedToTask;
use App\Section;
use App\Task;
use App\Project;
use App\Team;
use App\UserTask;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Traits\NotifyUserTrait;

class TaskController extends Controller
{
    use NotifyUserTrait;

    public function __construct() {
        /** define controller middleware */
        $this->middleware('auth:api');
    }

    /**
     * Check user can access task
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Team $team
     * @param \App\Project $project
     * @param \App\Section $section
     * @param \App\Task $task
     * @return \Illuminate\Http\Response
     */
    public function canAccess(Request $request, Team $team, Project $project, Section $section, Task $task){
        /** authorize user has access to task */
        $this->authorize('access-task', [$team, $project, $section, $task]);
        /** return success */
        return response()->json(['success' => true, 'message' => 'user can access task', 'task' => $task]);
    }

    /**
     * Store new task
     *
     * @param \Illuminate\Http\Request $request
     * @param Team $team
     * @param Project $project
     * @param Section $section
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Team $team, Project $project, Section $section) {
        /** authorize user has access to section */
        $this->authorize('access-section',[$team, $project,$section]);
        /** create ne task model */
        $task =  new Task();
        /** validate the request data */
        $this->validate(Request(),$task->validation, $task->messages);
        /** get current section task */
        $currentTasks = $section->tasks()->get();
        /** get sort order for new task */
        $sortOrder = count($currentTasks) + 1;
        /** create new task */
        $task = Task::create([
            'section_id' => $section->id,
            'name' => $request->get('name'),
            'due_date' => $request->get('due_date'),
            'due_time' => $request->get('due_time'),
            'sort_order' => $sortOrder,
            'priority_id' => $request->get('priority_id')['id'], /** vue-select component returns an object */
            'note' => $request->get('note')
        ]);
        /** assign task to users */
        if($request->has('users')){
            /**get users from request*/
            $userIds = array_pluck($request->users, 'id');
            /** add users to task */
            $task->assignedUsers()->attach($userIds);
            /** get logged in user */
            $loggedBy =  Auth::user();
            /** get all assigned users that are not user creating task */
            $users = $task->assignedUsers()->where('users.id', '<>', $loggedBy->id )->get();
            /** notify users they have been added to task */
            Notification::send($users, new UserAssignedToTask($team, $task, $loggedBy));
        }
        /** return success and stored task */
        return response()->json(['success' => true, 'message' => 'New task has been added to '.$section->name , 'task' => $task]);
    }

    /**
     * get task data
     *
     * @param Team $team
     * @param Project $project
     * @param Section $section
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team, Project $project, Section $section, Task $task) {
        /** authorize user has access to task */
        $this->authorize('access-section', [ $team, $project,$section, $task]);
        /** Get users assigned to task  */
        $task->assigned_users = $task->assignedUsers()->get();
        /** return success and requested task */
        return response()->json(['success' => true, 'message' => 'task has been found', 'task' => $task]);
    }

    /**
     * update task data
     *
     * @param \Illuminate\Http\Request $request
     * @param Team $team
     * @param Project $project
     * @param Section $section
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team, Project $project, Section $section, Task $task) {
        /** authorize user has access to task */
        $this->authorize('access-task', [$team, $project, $section, $task]);
        /** validate the task data */
        $this->validate(Request(),$task->validation, $task->messages);
        /** get current status_id to compare after update  */
        $oldStatus_id = $task->status_id;
        /** update record */
        $task->name = $request->name;
        $task->due_date =  $request->due_date;
        $task->note = $request->note;
        $task->sort_order = $request->sort_order;
        $task->priority_id = $request->priority_id;
        if($request->has('status_id')){
            $task->status_id = $request->status_id;
        }
        $task->save();
        /** check if status has change to done */
        if($oldStatus_id != 1 && $task->status_id == 1){
            /** notify assigned users that task has been completed */
            $this->notifyAssignedUsersTaskIsComplete($team, $project, $task);
        }
        /** get assigned userIds from request */
        $userIds = array_pluck($request->users, 'id');
        /** sync users assigned to task */
        $syncData = $task->assignedUsers()->sync($userIds);
        /** notify removed users */
        if($syncData['detached']){
            $this->notifyUsersRemovedFromTask($team, $task, $syncData['detached']);
        }
        /** notify newly assigned users */
        if($syncData['attached']){
            $this->notifyUsersAddedToTask($team, $task, $syncData['attached']);
        }
        /** return success and updated task */
        return response()->json(['success' => true, 'message' => 'task has been updated', 'task' => $task]);
    }
    /**
     * Flag task as done
     *
     * @param Team $team
     * @param Project $project
     * @param Section $section
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function done(Team $team, Project $project, Section $section, Task $task) {
        /** authorize user has access to task */
        $this->authorize('access-task', [$team, $project,$section, $task]);
        /** flag task as done */
        $task->status_id = 1;
        $task->save();
        /** notify assigned users that task is complete */
        $this->notifyAssignedUsersTaskIsComplete($team, $project, $task);
        /** return success message */
        return response()->json(['success' => true, 'message' => 'Task '.$task->name.' has been flagged as done', 'task' => $task]);
    }

    /**
     * Delete task
     *
     * @param Team $team
     * @param Project $project
     * @param Section $section
     * @param Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team, Project $project, Section $section,Task $task) {
        /** authorize user has access to task */
        $this->authorize('access-task', [$team, $project,$section, $task]);
        /** delete project */
        $task->delete();
        /** return success message */
        return response()->json(['success' => true, 'message' => 'Task '.$task->name.' has been successfully deleted']);
    }
}
