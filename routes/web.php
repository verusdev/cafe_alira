<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RefrigeratorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::post('/', [LandingController::class, 'store'])->name('landing.store');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('events', EventController::class);
    Route::get('events/{event}/shopping-list', [EventController::class, 'shoppingList'])->name('events.shopping-list');
    Route::get('calendar', [EventController::class, 'calendar'])->name('events.calendar');
    Route::get('export/events', [ExportController::class, 'events'])->name('export.events');
    Route::get('export/finance', [ExportController::class, 'finance'])->name('export.finance');
    Route::get('api/events', [EventController::class, 'calendarData'])->name('events.calendar-data');

    Route::resource('dishes', DishController::class);
    Route::resource('ingredients', IngredientController::class);
    Route::resource('refrigerators', RefrigeratorController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::post('purchases/{purchase}/complete', [PurchaseController::class, 'complete'])->name('purchases.complete');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
});
