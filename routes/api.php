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
//    /** Get logged in user */
//    Route::middleware('auth:api')->get('/user', function (Request $request) {
//        return $request->user();
//    });
    /** Project store, show, destroy */
    Route::resource('user', 'UserController', ['only' => ['index', 'store', 'show','update', 'destroy']]);
    /** get users projects */
    Route::get('/user/{user}/projects', ['uses'=>'UserController@projects', 'as'=>'User.projects'] );
/***********************
 * Project API
 **********************/
    /** Project index, store, show, destroy */
    Route::resource('project', 'ProjectController', ['only' => ['index', 'store', 'show','update', 'destroy']]);
/***********************
 * Section API
 **********************/
    /** Section show, update, destroy */
    Route::resource('section', 'SectionController', ['only' => [ 'show','update', 'destroy']]);
    /** Section store */
    Route::post('/project/{project}/section', ['uses'=>'SectionController@store', 'as'=>'Section.store'] );

/***********************
 * Task API
 **********************/
    /** Section store */
    Route::resource('section', 'SectionController', ['only' => [ 'show','update', 'destroy']]);
    /** Task store */
    Route::post('/project/{project}/section/{section}/task', ['uses'=>'TaskController@store', 'as'=>'Task.store'] );
