<?php

use App\Project;
use Illuminate\Http\Request;

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
    Route::post('/projects', 'ProjectController@store');
    Route::get('/projects', 'ProjectController@index');
    Route::get('/projects/create', 'ProjectController@create');
    Route::get('/projects/{project}', 'ProjectController@show');

    // project task
    Route::post('/projects/{project}/tasks', 'ProjectTaskController@store');

    Route::get('/home', 'HomeController@index')->name('home');
});


Auth::routes();
