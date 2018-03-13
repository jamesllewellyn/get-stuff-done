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

/***********************
 * User Team API
 **********************/
    /** UserTeam store, show, destroy */
    Route::apiResource('/user/{user}/team', 'UserTeamController', ['only' => ['index']]);
    /** update users current team */
    Route::put('/user/{user}/team', ['uses'=>'UserTeamController@update', 'as'=>'user.current_team'] );

/***********************
 * User Tasks API
 **********************/
    /** get users tasks */
    Route::get('/user/{user}/tasks', ['uses'=>'UserTaskController@index', 'as'=>'user.tasks'] );

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
    /** get users notifications */
    Route::get('/user/{user}/notifications', 'UserNotificationController@index');
    /** clear all users notifications */
    Route::delete('/user/{user}/clear-notifications', ['uses'=>'UserNotificationController@destroy', 'as'=>'user.notifications.clear'] );
    /** Mark notification as read */
    Route::put('/notification/{notification}', ['uses'=>'NotificationController@destroy', 'as'=>'notification.read'] );