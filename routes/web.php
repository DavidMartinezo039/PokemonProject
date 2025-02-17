<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\PDFController;
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

    Route::resource('user-sets', UserSetController::class);

    Route::get('user-sets/{userSetId}/generar-pdf', [PDFController::class, 'generatePDF'])->name('generar-pdf');

    Route::get('user-sets/{userSetId}/cards', [UserSetController::class, 'showCards'])->name('user-sets.cards');

    Route::get('user-sets/{userSetId}/select-card', [UserSetController::class, 'selectCard'])->name('user-sets.select-card');
    Route::post('user-sets/{userSetId}/card/{cardId}', [UserSetController::class, 'addCard'])->name('user-sets.add-card');
    Route::get('user-sets/{userSetId}/my-cards', [UserSetController::class, 'myCards'])->name('user-sets.my-cards');
    Route::delete('user-sets/{userSetId}/card/{cardId}', [UserSetController::class, 'removeCard'])->name('user-sets.remove-card');
});

require __DIR__.'/auth.php';
