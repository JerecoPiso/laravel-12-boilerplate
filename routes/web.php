<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::post('/login', [UserController::class, 'login']);
});

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');
