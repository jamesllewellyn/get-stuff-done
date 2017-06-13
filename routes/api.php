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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/***********************
 * Project API
 **********************/
    Route::resource('project', 'ProjectController',
                    ['only' => ['index', 'store', 'show','update', 'destroy']]);

    Route::group(['prefix'=>'/project/{project}'], function () {
        /** Project Section Routes */
        Route::post('/section', ['uses'=>'SectionController@store', 'as'=>'Section.store'] );
    });

/***********************
 * Section API
 **********************/
    Route::resource('section', 'SectionController',
                    ['only' => [ 'show','update', 'destroy']]);


/***********************
 * Task API
 **********************/
    Route::resource('section', 'SectionController',
                    ['only' => [ 'show','update', 'destroy']]);