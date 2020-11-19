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
});


Route::group(['middleware' => 'auth'], function () {

    // projects
    Route::resource('projects', 'ProjectController');

    // project task
    Route::post('/projects/{project}/tasks', 'ProjectTaskController@store');
    Route::patch('/projects/{project}/tasks/{task}', 'ProjectTaskController@update');

    // invitations
    Route::post('/projects/{project}/invitations', 'ProjectInvitationController@store');

    Route::get('/home', 'HomeController@index')->name('home');
});


Auth::routes();
