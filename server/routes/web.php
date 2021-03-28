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
    return view('login');
})->name('login');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/login-url/{uuid}', function (Illuminate\Http\Request $request) {
    $uuid = $request->uuid;

    return view('login_url', compact('uuid'));
})->name('login_url');
