<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SetController;
use App\Http\Controllers\UserSetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::resource('sets', SetController::class);
Route::get('/sets/{set}/cards', [SetController::class, 'showCards'])->name('sets.cards');
Route::resource('cards', CardController::class);

Route::post('/user-sets/{userSetId}/add-card/{cardId}', [UserSetController::class, 'addCardToUserSet']);
Route::post('/user-sets/{userSetId}/remove-card/{cardId}', [UserSetController::class, 'removeCardFromUserSet']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
