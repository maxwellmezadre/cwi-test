<?php

use App\Http\Controllers\ExternalController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\UsersDestroyController;
use App\Http\Controllers\UsersIndexController;
use App\Http\Controllers\UsersShowController;
use App\Http\Controllers\UsersStoreController;
use App\Http\Controllers\UsersUpdateController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\EnsureClientIsResourceOwner;

Route::get('/health', HealthController::class);

Route::middleware(EnsureClientIsResourceOwner::using('users.read'))
    ->group(function () {
        Route::get('/users', UsersIndexController::class);
        Route::get('/users/{user}', UsersShowController::class);
    });

Route::middleware(EnsureClientIsResourceOwner::using('users.write'))
    ->group(function () {
        Route::post('/users', UsersStoreController::class);
        Route::put('/users/{user}', UsersUpdateController::class);
        Route::delete('/users/{user}', UsersDestroyController::class);
    });

Route::get('/external', ExternalController::class)
    ->middleware(EnsureClientIsResourceOwner::using('external.read'));
