<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RefrigeratorController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('events', EventController::class);
    Route::get('events/{event}/shopping-list', [EventController::class, 'shoppingList'])->name('events.shopping-list');
    Route::get('calendar', [EventController::class, 'calendar'])->name('events.calendar');
    Route::get('api/events', [EventController::class, 'calendarData'])->name('events.calendar-data');

    Route::resource('dishes', DishController::class);
    Route::resource('ingredients', IngredientController::class);
    Route::resource('refrigerators', RefrigeratorController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::post('purchases/{purchase}/complete', [PurchaseController::class, 'complete'])->name('purchases.complete');
});
