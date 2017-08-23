<?php

namespace App\Policies;

use App\User;
use App\Team;
use App\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the project.
     *
     * @param  \App\User  $user
     * @param  \App\Team  $team
     * @param  \App\Project  $project
     * @return mixed
     */
    public function show(User $user, Project $project, Team $team)
    {
        /** get user team ids **/
        $userTeams = $user->teams()->pluck('id')->toArray();
        /** check user is in current team */
        if(!in_array($team->id, $userTeams)){
            return false;
        }
        /** check project is in current team */
        return $project->team()->first()->id == $team->id;
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @param  \App\Team  $team
     * @return mixed
     */
    public function update(User $user, Project $project, Team $team)
    {
        /** get user team ids **/
        $userTeams = $user->teams()->pluck('id')->toArray();
        /** check user is in current team */
        if(!in_array($team->id, $userTeams)){
            return false;
        }
        /** check project is in current team */
        return $project->team()->first()->id == $team->id;
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        //
    }
}
