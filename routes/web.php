<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\ServerController::class, 'showTweets'])->name('showTweets');
Route::get('getServerIp', [App\Http\Controllers\ServerController::class, 'getServerIp'])->name('getServerIp');
