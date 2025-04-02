<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('importCustomers', function () {
    app('App\Http\Controllers\STPCardsController')->importCustomers();
});
Artisan::command('createTestUser', function () {
    app('App\Http\Controllers\STPCardsController')->createTestUser();
});
