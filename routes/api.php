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

/*  API v1  */
Route::prefix('v1')->group(function ()
{
    // Get Users
    Route::prefix('user')->group(function () {
        Route::get('all', 'UserController@all');
        Route::post('update', 'UserController@update');
        Route::post('delete', 'UserController@delete');
    });

    // Get team info
    Route::get('team', 'RolesController@getTeam');
    Route::prefix('role')->group(function () {
        Route::get('all', 'RolesController@all');
        Route::post('create', 'RolesController@create');
        Route::post('delete', 'RolesController@delete');
        Route::post('update', 'RolesController@update');
    });

    // Auth stuff for vue.
    Route::prefix('auth')->group(function () {
        Route::get('refresh', 'AuthController@refresh');
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('register', 'AuthController@register');

        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('user', 'AuthController@user');
            Route::post('logout', 'AuthController@logout');
            Route::post('checkperm', 'AuthController@checkPerms');
        });
    });

    // Song Stuff
    Route::prefix('song')->group(function () {
        Route::get('current', 'SongController@current');
        Route::get('recent', 'SongController@recent');
    });

    // Timetable Stuff
    Route::prefix('timetable')->group(function () {
        Route::get('/get', 'TimetableController@get');
        Route::get('/currentSlot', 'TimetableController@currentSlot');
        Route::post('/claim', 'TimetableController@claimSlot');
        Route::post('/unclaim', 'TimetableController@unclaimSlot');
    });

    // Site Info
    Route::prefix('settings')->group(function () {
        Route::get('/get', 'SettingsController@getSettings');
        Route::post('/update', 'SettingsController@updateSettings');
        Route::post('/addrule', 'SettingsController@addRule');
        Route::post('/addbsong', 'SettingsController@addBSong');
        Route::post('/delrule', 'SettingsController@delRule');
        Route::post('/delbsong', 'SettingsController@delBSong');
    });

    // Request Stuff
    Route::prefix('request')->group(function () {
        Route::get('/all', "RequestsController@all");
        Route::post('/delete', "RequestsController@delete");
        Route::post('/send', "RequestsController@create");
    });

    Route::prefix('radio')->group(function () {
        Route::get('listeners', "StatsController@listeners");
    });
});
