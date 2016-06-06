<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

// API
Route::get('event/{event_id}/wall', 'ApiController@getWall');
Route::get('event/{event_id}/user', 'ApiController@getUserJson');
Route::get('event/{event_id}/{min_id}', 'ApiController@getJson');
Route::get('event/{event_id}', 'ApiController@getJson');

Route::group(['middleware' => ['web']], function () {

    Route::get('dashboard', 'DashboardController@index');

    Route::get('instagram/callback', 'InstagramController@callback');

// Settings
//Route::get('brand/{id}/edit', 'SettingController@brand');
//Route::patch('brand/{id}/edit', 'SettingController@brand');

// System
    Route::get('system/testcode', 'SystemController@testCode');
    Route::get('system/testgetmedia', 'SystemController@testGetMedia');
    Route::get('system/runschedule', 'SystemController@runSchedule');

//api
    Route::get('instagram/user/{user_id}', 'ViewerController@getUser');
    Route::get('instagram/tag/{id}', 'ViewerController@getTag');
    Route::get('instagram/search/{search}', 'ViewerController@search');

    Route::get('instagram/myfeed', 'ViewerController@getMyFeed');
    Route::get('instagram/mymedia', 'ViewerController@getMyMedia');
    Route::get('instagram/mylikes', 'ViewerController@getMyLikes');
    Route::get('instagram/myfollowers', 'ViewerController@getMyFollowers');
    Route::get('instagram/myfollowings', 'ViewerController@getMyFollowings');
    Route::get('instagram/followers/{user_id}', 'ViewerController@getFollowers');
    Route::get('instagram/followings/{user_id}', 'ViewerController@getFollowings');
    Route::get('instagram/populars', 'ViewerController@getPopular');

    Route::post('instagram/next', 'ViewerController@postNext');

    Route::get('viewer', 'ViewerController@index');

    // Event
    Route::get('event', 'EventController@index');
    Route::get('event/getnewmedia', 'EventController@getNewMedia');
    Route::get('event/updatenewmedia', 'EventController@updateNewMedia');
    Route::get('mvent/{id}/choose', 'EventController@choose');
    Route::resource('mvent', 'EventController');

    Route::controller('media', 'MediaController');

});

// Dance Fat Off
Route::get('api/video/tag', 'ApiController@getVideo');
Route::get('api/media/tag', 'ApiController@getMedia');


// Miscs

Route::get('debug', 'ApiController@debug');
Route::get('api', 'ApiController@index');
Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
});
