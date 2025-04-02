<?php

use Illuminate\Support\Facades\Route;

Route::get('/','App\Http\Controllers\BEController@dashboard')->middleware('auth');
Route::get('/login', ['uses' => 'App\Http\Controllers\BEController@loginForm', 'as' => 'login']);
Route::post('/login', 'App\Http\Controllers\BEController@login');
Route::get('/logout', 'App\Http\Controllers\BEController@logout');
