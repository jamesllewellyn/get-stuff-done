<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/** create new team */
Route::get('/create', function () {
    return view('auth.create-team');
});

/** user invitation */
Route::get('invitation', 'InvitationController@show')->name('team.invitation.show');
Route::post('invitation', 'InvitationController@createUserFromInvitation')->name('team.invitation.createUser');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

