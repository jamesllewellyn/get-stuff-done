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
 * User API
 **********************/
    /** Project store, show, destroy */
    Route::resource('user', 'UserController', ['only' => ['index', 'store', 'show','update', 'destroy']]);
    /** get users projects */
    Route::get('/user/{user}/projects', ['uses'=>'UserController@projects', 'as'=>'User.projects'] );
/***********************
 * Project API
 **********************/
    /** Project index, store, show, destroy */
    Route::resource('project', 'ProjectController', ['only' => ['index', 'store', 'show','update', 'destroy']]);
    /** get all project sections */
    Route::get('/project/{project}/sections', ['uses'=>'ProjectController@sections', 'as'=>'Project.sections'] );
/***********************
 * Section API
 **********************/
    /** Section show, update, destroy */
    Route::resource('section', 'SectionController', ['only' => [ 'show','update', 'destroy']]);
    /** Section store */
    Route::post('/project/{project}/section', ['uses'=>'SectionController@store', 'as'=>'Section.store'] );
    /** reorder section tasks */
    Route::put('/project/{project}/section/{section}/tasks/reorder', ['uses'=>'SectionController@reorderTasks', 'as'=>'Section.reorderTasks'] );

/***********************
 * Task API
 **********************/
    /** Section store */
    Route::resource('section', 'SectionController', ['only' => [ 'show', 'destroy']]);
    /** Task store */
    Route::post('/project/{project}/section/{section}/task', ['uses'=>'TaskController@store', 'as'=>'Task.store'] );
    /** Update task */
    Route::put('/project/{project}/section/{section}/task/{task}', ['uses'=>'TaskController@update', 'as'=>'Task.update'] );
    /** flag task as done */
    Route::put('/task/{task}/done', ['uses'=>'TaskController@done', 'as'=>'Task.done'] );

