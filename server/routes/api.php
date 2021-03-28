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


Route::get('/', function () {
    return [
        'app' => 'Laravel 6 API Boilerplate',
        'version' => '1.0.0',
    ];
});


Route::group(['namespace' => 'Auth', "prefix" => "auth"], function () {

    Route::post('login', ['as' => 'login', 'uses' => 'AuthController@login']);

    Route::post('login-url/{uuid}', ['as' => 'login_url', 'uses' => 'AuthController@loginUrl']);
    
    // Send reset password mail
    Route::post('recovery', 'ForgotPasswordController@sendPasswordResetLink');
    // handle reset password form process
    Route::post('reset', 'ResetPasswordController@callResetPassword');
    // handle reset password form process
    Route::get('verify', 'VerifyAccountController@verify');

});

Route::group(['middleware' => ['jwt', 'jwt.auth']], function () {

    Route::group(['namespace' => 'Profile', "prefix" => "profile"], function () {

        Route::get('/', ['as' => 'profile', 'uses' => 'ProfileController@me']);

        Route::put('/', ['as' => 'profile', 'uses' => 'ProfileController@update']);

        Route::put('password', ['as' => 'profile', 'uses' => 'ProfileController@updatePassword']);

    });

    Route::group(['namespace' => 'Auth', "prefix" => "auth"], function () {

        Route::post('register', ['as' => 'register', 'uses' => 'RegisterController@register']);
        Route::post('logout', ['as' => 'logout', 'uses' => 'LogoutController@logout']);

    });

    Route::group(["prefix" => "calendar"], function () {
        Route::get('events', 'CalendarController@getEvents');
        Route::get('getUserEvents', 'CalendarController@getUserEvents');
        Route::get('addUnallocatedPeriod', 'CalendarController@addNotAllocatedEvents');
    });

    Route::group(["prefix" => "search"], function () {
        Route::post('/', 'SearchController@search');
        Route::post('/event', 'SearchController@getEvent');
    });

    Route::group(["prefix" => "action"], function () {
        Route::post('book', 'ActionsController@book');
        Route::post('unbook', 'ActionsController@unbook');
        Route::put('updateActionState', 'ActionsController@updateActionState');
        Route::get('waitingActions', 'ActionsController@waitingActions');
        Route::put('updateEvent', 'ActionsController@updateEvent');
    });

    Route::group(["prefix" => "language"], function () {
        Route::get('msg', 'LanguageController@getMsg');
    });

    Route::group(["prefix" => "info"], function () {
        Route::get('/', 'InfoController@getAll');
        Route::get('events', 'InfoController@events');
    });
});


Route::group(["prefix" => "test"], function () {
    Route::get('1', 'TestController@test1');
});

