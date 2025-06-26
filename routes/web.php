<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MuddaDartaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
Route::get('/', [FrontendController::class, 'index'])->name('home');

Auth::routes();
Route::get('/home', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/mudda_darta', [MuddaDartaController::class, 'index'])->name('mudda_darta.index');
Route::get('/get_mudda', [MuddaDartaController::class, 'getMudda'])->name('mudda_darta.get_mudda');;
Route::get('/mudda_darta/create', [MuddaDartaController::class, 'create'])->name('mudda_darta.create');
Route::post('/mudda_darta/store', [MuddaDartaController::class, 'store'])->name('mudda_darta.store');
Route::get('/mudda_darta/{id}/edit', [MuddaDartaController::class, 'edit'])->name('mudda_darta.edit');
Route::post('/mudda_darta/update/{id}', [MuddaDartaController::class, 'update'])->name('mudda_darta.update');
Route::delete('/mudda_darta/delete/{id}', [MuddaDartaController::class, 'destroy'])->name('mudda_darta.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
