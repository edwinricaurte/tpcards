<?php

use Illuminate\Support\Facades\Route;

Route::get('/','App\Http\Controllers\BEController@dashboard')->middleware('auth');
Route::get('/get-customers','App\Http\Controllers\BEController@getCustomers')->middleware('auth');
Route::get('/get-average-spend','App\Http\Controllers\BEController@getAverageSpend')->middleware('auth');
Route::get('/get-loyalty-points','App\Http\Controllers\BEController@getLoyaltyPoints')->middleware('auth');

Route::get('/login', ['uses' => 'App\Http\Controllers\BEController@loginForm', 'as' => 'login']);
Route::post('/login', 'App\Http\Controllers\BEController@login');
Route::get('/logout', 'App\Http\Controllers\BEController@logout');
