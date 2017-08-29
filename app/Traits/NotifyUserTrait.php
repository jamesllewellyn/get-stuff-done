<?php

namespace App\Traits;

use App\User;
use App\Task;
use App\Team;
use App\Notifications\UserRemovedFromTask;
use App\Notifications\UserAssignedToTask;
use Illuminate\Support\Facades\Notification;

trait NotifyUserTrait
{
    /**
     * Notify all users that have been removed from task
     *
     * @param Team $team
     * @param Task $task
     * @param User $actionBy
     * @param $userIds
     */
    private function notifyUsersRemovedFromTask(Team $team, Task $task, User $actionBy, $userIds){
        $users = User::whereIn('id',$userIds)->get();
        Notification::send($users, new UserRemovedFromTask($team, $task, $actionBy));
    }

    /**
     * Notify all users that have been added to task
     *
     * @param Team $team
     * @param Task $task
     * @param User $actionBy
     * @param $userIds
     */
    private function notifyUsersAddedToTask(Team $team, Task $task, User $actionBy, $userIds){
        $users = User::whereIn('id',$userIds)->get();
        Notification::send($users, new UserAssignedToTask($team, $task, $actionBy));
    }
}