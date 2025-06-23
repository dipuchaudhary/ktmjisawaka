<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DartaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
Route::get('/', [FrontendController::class, 'index'])->name('home');

Auth::routes();
Route::get('/home', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/mudda_darta', [DartaController::class, 'create'])->name('mudda_darta.create');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
