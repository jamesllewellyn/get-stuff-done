<?php

namespace App\Traits;

use App\User;
use App\Task;
use App\Team;
use App\Project;
use Auth;
use App\Notifications\UserRemovedFromTask;
use App\Notifications\UserAssignedToTask;
use App\Notifications\ProjectAdded;
use App\Notifications\ProjectDeleted;
use App\Notifications\UserAssignedTaskCompleted;
use Illuminate\Support\Facades\Notification;

trait NotifyUserTrait
{
    /**
     * Notify all users that have been removed from task
     *
     * @param Team $team
     * @param Task $task
     * @param $userIds
     */
    private function notifyUsersRemovedFromTask(Team $team, Task $task, $userIds){
        /** get users to be notified  */
        $users = User::whereIn('id',$userIds)->where('id', '<>', Auth::User()->id )->get();
        /** send notifications  */
        Notification::send($users, new UserRemovedFromTask($team, $task, Auth::User()));
    }

    /**
     * Notify all users that have been added to task
     *
     * @param Team $team
     * @param Task $task
     * @param $userIds
     */
    private function notifyUsersAddedToTask(Team $team, Task $task, $userIds){
        /** get users to be notified  */
        $users = User::whereIn('id',$userIds)->where('id', '<>', Auth::User()->id )->get();
        /** send notifications  */
        Notification::send($users, new UserAssignedToTask($team, $task, Auth::User()));
    }

    /**
     * Notify team members that project has been added
     *
     * @param Team $team
     * @param Project $project
     */
    private function notifyTeamMembersProjectAdded(Team $team, Project $project){
        /** get team members  */
        $users = $team->users()->where('users.id', '<>', Auth::User()->id)->get();
        /** send notifications  */
        if($users->isNotEmpty()){
            Notification::send($users, new ProjectAdded($team, $project, Auth::User()));
        }
    }

    /**
     * Notify team members that project has been deleted
     *
     * @param Team $team
     * @param $projectName
     */
    private function notifyTeamMembersProjectDeleted(Team $team, $projectName){
        /** get team members  */
        $users = $team->users()->where('users.id', '<>', Auth::User()->id)->get();
        /** send notifications  */
        if($users->isNotEmpty()){
            Notification::send($users, new ProjectDeleted($team, $projectName, Auth::User()));
        }
    }

    /**
     * Notify assigned user that task is complete
     *
     * @param Team $team
     * @param Project $project
     * @param Task $task
     */
    private function notifyAssignedUsersTaskIsComplete(Team $team, Project $project, Task $task){
        /** get team members  */
        $users = $task->assignedUsers()->where('users.id', '<>', Auth::User()->id)->get();
        /** send notifications  */
        if($users->isNotEmpty()){
            Notification::send($users, new UserAssignedTaskCompleted($team, $project, $task, Auth::User()));
        }
    }
}