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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


// Rutas para añadir y eliminar cartas de un set
    Route::get('user-sets', [UserSetController::class, 'index'])->name('user-sets.index');
    Route::post('user-sets/{userSetId}/add-card/{cardId}', [UserSetController::class, 'addCard'])->name('user-sets.add-card');
    Route::post('user-sets/{userSetId}/remove-card/{cardId}', [UserSetController::class, 'removeCard'])->name('user-sets.remove-card');
    Route::get('/user-sets/{userSetId}', [UserSetController::class, 'show'])->name('user-sets.show');
});

require __DIR__.'/auth.php';
