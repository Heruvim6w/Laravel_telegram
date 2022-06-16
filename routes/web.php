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

Route::any('/search', '\App\Http\Controllers\TelegramController@searchContact');
Route::any('/logout', '\App\Http\Controllers\TelegramController@logout');
Route::get('/start', '\App\Http\Controllers\TelegramController@start');
Route::post('/start', '\App\Http\Controllers\TelegramController@start');
Route::any('/me', '\App\Http\Controllers\TelegramController@me');
