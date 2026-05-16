<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    Route::name('api.')->group(function () {
        Route::apiResource('events', EventController::class);
        Route::get('events/{event}/shopping-list', [EventController::class, 'shoppingList'])->name('events.shopping-list');
    });
});
