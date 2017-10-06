<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        /** team gate */
        Gate::define('access-team', function ($user, $team) {
            /** get user team ids **/
            $userTeams = $user->teams()->pluck('teams.id')->toArray();
            /** check user is in current team */
            return in_array($team->id, $userTeams);
        });
        /** project gate */
        Gate::define('access-project', function ($user, $team, $project) {
            /** get user team ids **/
            $userTeams = $user->teams()->pluck('teams.id')->toArray();
            /** check user is in current team */
            if(!in_array($team->id, $userTeams)){
                return false;
            }
            /** check project is in current team */
            return $project->team()->first()->id == $team->id;
        });
        /** project gate */
        Gate::define('access-section', function ($user, $team, $project, $section) {
            /** get user team ids **/
            $userTeams = $user->teams()->pluck('teams.id')->toArray();
            /** check user is in current team */
            if(!in_array($team->id, $userTeams)){
                return false;
            }
            /** check project is in current team */
            if(!$project->team()->first()->id == $team->id){
                return false;
            }
            /** check section is in project */
            return $section->project_id == $project->id;
        });
        /** tasks gate */
        Gate::define('access-task', function ($user, $team, $project, $section, $task) {
            /** get user team ids **/
            $userTeams = $user->teams()->pluck('teams.id')->toArray();
            /** check user is in current team */
            if(!in_array($team->id, $userTeams)){
                return false;
            }
            /** check project is in current team */
            if(!$project->team()->first()->id == $team->id){
                return false;
            }
            /** check section is in project */
            if(!$section->project_id == $project->id){
                return false;
            }
            /** check task is in section */
            return $task->section_id == $section->id ;
        });

        /** notification gate */
        Gate::define('access-notification', function ($user, $notification) {
            /** get user team ids **/
            $notifications = $user->unreadNotifications()->pluck('notifications.id')->toArray();
            /** check user is in current team */
            return in_array($notification->id, $notifications);
        });
    }
}
