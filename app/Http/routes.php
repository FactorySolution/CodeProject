<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::post('oauth/access_token', function() {
   return \Illuminate\Support\Facades\Response::json(\LucaDegasperi\OAuth2Server\Facades\Authorizer::issueAccessToken());
});

Route::get('client','ClientController@index');
Route::post('client','ClientController@store');
Route::get('client/{id}','ClientController@show');
Route::delete('client/{id}','ClientController@destroy');
Route::put('client/{id}','ClientController@update');

Route::get('project/{id}/note','ProjectNoteController@index');
Route::post('project/{id}/note','ProjectNoteController@store');
Route::get('project/{id}/note/{noteId}','ProjectNoteController@show');
Route::put('project/{id}/note/{noteId}','ProjectNoteController@update');
Route::delete('project/{id}/note/{noteId}','ProjectNoteController@delete');

Route::get('project','ProjectController@index');
Route::get('project/{id}','ProjectController@show');
Route::post('project','ProjectController@store');
Route::delete('project/{id}','ProjectController@destroy');
Route::put('project/{id}','ProjectController@update');

Route::get('project/{id}/members', 'ProjectMemberController@members');
Route::get('project/{id}/member/{userId}', 'ProjectMemberController@isMember');
Route::post('project/{id}/member/{userId}', 'ProjectMemberController@addMember');
Route::delete('project/{id}/member/{userId}', 'ProjectMemberController@removeMember');

Route::get('project/task', 'ProjectTaskController@index');
Route::post('project/task', 'ProjectTaskController@store');
Route::get('project/task/{id}', 'ProjectTaskController@show');
Route::put('project/task/{id}', 'ProjectTaskController@update');
Route::delete('project/task/{id}', 'ProjectTaskController@destroy');

