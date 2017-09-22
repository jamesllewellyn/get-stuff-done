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
    Route::resource('team', 'TeamController', ['only' => ['show','update', 'destroy']]);
    /** Create new team */
    Route::post('/user/{user}/team', ['uses'=>'TeamController@store', 'as'=>'team.store'] );
    /** Get Teams Projects */
    Route::get('/team/{team}/projects', ['uses'=>'TeamController@projects', 'as'=>'team.projects'] );
    /** add user to team */
    Route::post('/team/{team}/user/', ['uses'=>'TeamController@user', 'as'=>'team.user'] );
    /** get team overview */
    Route::get('/team/{team}/overview/', ['uses'=>'TeamController@overview', 'as'=>'team.overview'] );

/***********************
 * User API
 **********************/
    /** Project store, show, destroy */
    Route::resource('user', 'UserController', ['only' => ['index', 'store', 'show','update', 'destroy']]);
    /** get users teams */
    Route::get('/user/{user}/teams', ['uses'=>'UserController@teams', 'as'=>'user.teams'] );
    /** set users avatar */
    Route::post('/user/{user}/avatar', ['uses'=>'UserController@avatar', 'as'=>'user.store.avatar'] );
    /** update users current team */
    Route::put('/user/{user}/team', ['uses'=>'UserController@updateTeam', 'as'=>'user.current_team'] );
    /** get users current tasks */
    Route::get('/user/{user}/tasks', ['uses'=>'UserController@tasks', 'as'=>'user.tasks'] );
    /** get users over due tasks */
    Route::get('/user/{user}/over-due', ['uses'=>'UserController@overDue', 'as'=>'user.overDue'] );
    /** get tasks user is working on */
    Route::get('/user/{user}/working-on-it', ['uses'=>'UserController@workingOnIt', 'as'=>'user.workingOnIt'] );
    /** get users unread notifications */
    Route::get('/user/{user}/notifications', ['uses'=>'UserController@notifications', 'as'=>'user.notifications'] );
    /** mark all users notifications as read */
    Route::put('/user/{user}/clear-notifications', ['uses'=>'UserController@clearNotifications', 'as'=>'user.notifications.clear'] );

/***********************
 * Project API
 **********************/
    /** Project store, show, destroy */
    Route::resource('/team/{team}/project', 'ProjectController', ['only' => ['store', 'show','update', 'destroy']]);
    /** get all project sections */
    Route::get('/team/{team}/project/{project}/sections', ['uses'=>'ProjectController@sections', 'as'=>'project.sections'] );
    /** check user can access project */
    Route::get('/team/{team}/project/{project}/can-access', ['uses'=>'ProjectController@canAccess', 'as'=>'project.canAccess'] );
/***********************
 * Section API
 **********************/
    /** Section store, show, update, destroy */
    Route::resource('/team/{team}/project/{project}/section', 'SectionController', ['only' => ['store' ,'show', 'update','destroy']]);
    /** reorder section tasks */
    Route::put('/team/{team}/project/{project}/section/{section}/tasks/reorder', ['uses'=>'SectionController@reorderTasks', 'as'=>'section.reorderTasks'] );
/***********************
 * Task API
 **********************/
    /** Section store, show, destroy, update */
    Route::resource('team/{team}/project/{project}/section/{section}/task', 'TaskController', ['only' => [ 'store', 'show', 'destroy', 'update']]);
    /** flag task as done */
    Route::put('team/{team}/project/{project}/section/{section}/task/{task}/done', ['uses'=>'TaskController@done', 'as'=>'task.done'] );
    /** check user can access project */
    Route::get('/team/{team}/project/{project}/section/{section}/task/{task}/can-access', ['uses'=>'TaskController@canAccess', 'as'=>'project.canAccess'] );
/***********************
 * Notifications API
 **********************/
    /** Mark notification as read */
    Route::put('/notification/{notification}', ['uses'=>'NotificationController@markAsRead', 'as'=>'notification.read'] );