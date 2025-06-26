<?php

use App\Http\Controllers\BankingMuddaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MuddaDartaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PatraChallaniController;

Route::get('/', [FrontendController::class, 'index'])->name('home');

Auth::routes();
Route::get('/home', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// mudda darta routes
Route::get('/mudda-darta', [MuddaDartaController::class, 'index'])->name('mudda_darta.index');
Route::get('/get-mudda', [MuddaDartaController::class, 'getMudda'])->name('mudda_darta.get_mudda');;
Route::get('/mudda-darta/create', [MuddaDartaController::class, 'create'])->name('mudda_darta.create');
Route::post('/mudda-darta/store', [MuddaDartaController::class, 'store'])->name('mudda_darta.store');
Route::get('/mudda-darta/{id}/edit', [MuddaDartaController::class, 'edit'])->name('mudda_darta.edit');
Route::post('/mudda-darta/update/{id}', [MuddaDartaController::class, 'update'])->name('mudda_darta.update');
Route::delete('/mudda-darta/delete/{id}', [MuddaDartaController::class, 'destroy'])->name('mudda_darta.destroy');
//banking mudda routes
Route::get('/banking-mudda', [BankingMuddaController::class, 'index'])->name('banking_mudda.index');
Route::get('/banking-mudda/create', [BankingMuddaController::class, 'create'])->name('banking_mudda.create');
Route::post('/banking-mudda/store', [BankingMuddaController::class, 'store'])->name('banking_mudda.store');
Route::get('/banking-mudda/{id}/edit', [BankingMuddaController::class, 'edit'])->name('banking_mudda.edit');
Route::post('/banking-mudda/update/{id}',[BankingMuddaController::class, 'update'])->name('banking_mudda.update');
Route::delete('/banking-mudda/delete/{id}', [BankingMuddaController::class, 'destroy'])->name('banking_mudda.destroy');
//patra challani routes
Route::get('/patra-challani', [PatraChallaniController::class, 'index'])->name('patra_challani.index');
Route::get('/patra-challani/create', [PatraChallaniController::class, 'create'])->name('patra_challani.create');
Route::post('/patra-challani/store', [PatraChallaniController::class, 'store'])->name('patra_challani.store');
Route::get('/patra-challani/{id}/edit', [PatraChallaniController::class, 'edit'])->name('patra_challani.edit');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
