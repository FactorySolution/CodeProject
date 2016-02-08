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

Route::group(['middleware' => 'oauth'], function (){

    Route::resource('client', 'ClientController', ['except' => ['create', 'edit']]);

    Route::resource('project', 'ProjectController', ['except' => ['create', 'edit']]);

    Route::group(['prefix' => 'project'], function(){


        /**
         * Project Notes
         */

        Route::get('{id}/note','ProjectNoteController@index');
        Route::post('{id}/note','ProjectNoteController@store');
        Route::get('{id}/note/{noteId}','ProjectNoteController@show');
        Route::put('{id}/note/{noteId}','ProjectNoteController@update');
        Route::delete('{id}/note/{noteId}','ProjectNoteController@delete');

        /**
         * Project Task
         */

        Route::get('task', 'ProjectTaskController@index');
        Route::post('task', 'ProjectTaskController@store');
        Route::get('task/{id}', 'ProjectTaskController@show');
        Route::put('task/{id}', 'ProjectTaskController@update');
        Route::delete('task/{id}', 'ProjectTaskController@destroy');

        /**
         * Project Member
         */

        Route::get('{id}/members', 'ProjectController@members');
        Route::get('{id}/member/{userId}', 'ProjectController@isMember');
        Route::post('{id}/member/{userId}', 'ProjectController@addMember');
        Route::delete('{id}/member/{userId}', 'ProjectController@removeMember');

        /**
         * Project File
         */

        Route::post('{id}/file', 'ProjectFileController@store');
        Route::delete('{id}/file', 'ProjectFileController@destroy');

    });
});










