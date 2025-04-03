<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/get-loyalty-points-stats', 'App\Http\Controllers\BEController@getLoyaltyPointsStats');
