<?php

use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SetController;
use App\Http\Controllers\Api\UserSetController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [ProfileController::class, 'login']);
Route::post('/register', [ProfileController::class, 'register']);

Route::get('/sets', [SetController::class, 'index']);
Route::get('/sets/{set}', [SetController::class, 'show']);
Route::get('/sets/{set}/cards', [SetController::class, 'showCards']);
Route::get('/cards', [CardController::class, 'index']);
Route::get('/cards/{card}', [CardController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    Route::resource('user-sets', UserSetController::class);

    Route::get('user-sets/{userSet}/select-card', [UserSetController::class, 'selectCard']);
    Route::post('user-sets/{userSet}/card/{card}', [UserSetController::class, 'addCard']);
    Route::get('user-sets/{userSet}/my-cards', [UserSetController::class, 'myCards']);
    Route::delete('user-sets/{userSet}/card/{card}', [UserSetController::class, 'removeCard']);
});
