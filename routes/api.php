<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    Route::name('api.')->group(function () {
        Route::apiResource('events', EventController::class);
        Route::get('events/{event}/shopping-list', [EventController::class, 'shoppingList'])->name('events.shopping-list');
    });

    Route::name('api.notifications.')->group(function () {
        Route::get('notifications', [NotificationController::class, 'index'])->name('index');
        Route::post('notifications/{id}/read', [NotificationController::class, 'read'])->name('read');
        Route::post('notifications/read-all', [NotificationController::class, 'readAll'])->name('read-all');
    });
});
