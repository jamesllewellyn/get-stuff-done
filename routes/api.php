<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/***********************
 * Team API
 **********************/
    /** Team API */
    Route::apiResource('team', 'TeamController', ['only' => ['update', 'store']]);
    /** Get Teams Projects */
    Route::get('/team/{team}/projects', ['uses'=>'TeamController@getProjects', 'as'=>'team.projects'] );
    /** invite user to team */
    Route::post('/team/{team}/user', ['uses'=>'InvitationController@store', 'as'=>'team.invitation.store'] );
    /** get team overview */
    Route::get('/team/{team}/overview', ['uses'=>'TeamController@getOverview', 'as'=>'team.overview'] );

/***********************
 * User API
 **********************/
    /** Project store, show, destroy */
    Route::apiResource('user', 'UserController', ['only' => ['index', 'store', 'update', 'destroy']]);
    /** get users teams */
//    Route::get('/user/{user}/teams', ['uses'=>'UserController@getTeams', 'as'=>'user.teams'] );
    /** update users current team */
//    Route::put('/user/{user}/team', ['uses'=>'UserController@updateTeam', 'as'=>'user.current_team'] );
    /** get users current tasks */
    Route::get('/user/{user}/tasks', ['uses'=>'UserController@getTasks', 'as'=>'user.tasks'] );
    /** get users over due tasks */
    Route::get('/user/{user}/over-due', ['uses'=>'UserController@getOverDue', 'as'=>'user.overDue'] );
    /** get tasks user is working on */
    Route::get('/user/{user}/working-on-it', ['uses'=>'UserController@getWorkingOnIt', 'as'=>'user.workingOnIt'] );
    /** get users unread notifications */
    Route::get('/user/{user}/notifications', ['uses'=>'UserController@getNotifications', 'as'=>'user.notifications'] );
    /** mark all users notifications as read */
    Route::put('/user/{user}/clear-notifications', ['uses'=>'UserController@clearNotifications', 'as'=>'user.notifications.clear'] );

/***********************
 * User Team API
 **********************/
    /** UserTeam store, show, destroy */
    Route::apiResource('/user/{user}/team', 'UserTeamController', ['only' => ['index']]);
    /** update users current team */
    Route::put('/user/{user}/team', ['uses'=>'UserTeamController@update', 'as'=>'user.current_team'] );

/***********************
 * Project API
 **********************/
    /** Project store, show, destroy */
    Route::apiResource('/team/{team}/project', 'ProjectController', ['only' => ['store', 'show', 'update', 'destroy']]);
    /** check if user can access project */
    Route::get('/team/{team}/project/{project}/can-access', ['uses'=>'ProjectController@canAccess', 'as'=>'project.canAccess'] );

/***********************
 * Section API
 **********************/
    /** Section store, show, update, destroy */
    Route::apiResource('/team/{team}/project/{project}/section', 'SectionController', ['only' => ['store' , 'update', 'destroy']]);
/***********************
 * Task API
 **********************/
    /** Task store, show, destroy, update */
    Route::apiResource('team/{team}/project/{project}/section/{section}/task', 'TaskController', ['only' => [ 'store', 'show', 'destroy', 'update']]);
    /** flag task as done */
    Route::put('team/{team}/project/{project}/section/{section}/task/{task}/done', ['uses'=>'TaskController@done', 'as'=>'task.done'] );
    /** check user can access task */
    Route::get('/team/{team}/project/{project}/section/{section}/task/{task}/can-access', ['uses'=>'TaskController@canAccess', 'as'=>'task.canAccess'] );
/***********************
 * Notifications API
 **********************/
    /** Mark notification as read */
    Route::put('/notification/{notification}', ['uses'=>'NotificationController@markAsRead', 'as'=>'notification.read'] );