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

Route::post('/start', '\App\Http\Controllers\TelegramController@start');
Route::get('/logout', '\App\Http\Controllers\TelegramController@logout');
Route::get('/me', '\App\Http\Controllers\TelegramController@me');
