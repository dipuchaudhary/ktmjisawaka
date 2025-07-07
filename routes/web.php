<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AviyogChallaniController;
use App\Http\Controllers\BankingMuddaController;
use App\Http\Controllers\ChallaniController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MuddaDartaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PatraChallaniController;
use App\Http\Controllers\PunarabedanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Models\AviyogChallani;

Route::get('/', [FrontendController::class, 'index'])->name('home');

Auth::routes();

Route::get('/punarabedan',[PunarabedanController::class,'index'])->name('punarabedan.index');
Route::get('/mudda-darta', [MuddaDartaController::class, 'index'])->name('mudda_darta.index');
Route::get('/banking-mudda', [BankingMuddaController::class, 'index'])->name('banking_mudda.index');
Route::get('/patra-challani', [PatraChallaniController::class, 'index'])->name('patra_challani.index');
Route::get('/aviyog-challani', [AviyogChallaniController::class, 'index'])->name('aviyog_challani.index');
// mudda darta routes
Route::group(['middleware' => ['auth']], function() {
Route::get('/mudda-darta/create', [MuddaDartaController::class, 'create'])->name('mudda_darta.create');
Route::post('/mudda-darta/store', [MuddaDartaController::class, 'store'])->name('mudda_darta.store');
Route::get('/mudda-darta/{id}/edit', [MuddaDartaController::class, 'edit'])->name('mudda_darta.edit');
Route::post('/mudda-darta/update/{id}', [MuddaDartaController::class, 'update'])->name('mudda_darta.update');
Route::delete('/mudda-darta/delete/{id}', [MuddaDartaController::class, 'destroy'])->name('mudda_darta.destroy');
//banking mudda routes

Route::get('/banking-mudda/create', [BankingMuddaController::class, 'create'])->name('banking_mudda.create');
Route::post('/banking-mudda/store', [BankingMuddaController::class, 'store'])->name('banking_mudda.store');
Route::get('/banking-mudda/{id}/edit', [BankingMuddaController::class, 'edit'])->name('banking_mudda.edit');
Route::post('/banking-mudda/update/{id}',[BankingMuddaController::class, 'update'])->name('banking_mudda.update');
Route::delete('/banking-mudda/delete/{id}', [BankingMuddaController::class, 'destroy'])->name('banking_mudda.destroy');
//patra challani routes
Route::get('/patra-challani/create', [PatraChallaniController::class, 'create'])->name('patra_challani.create');
Route::post('/patra-challani/store', [PatraChallaniController::class, 'store'])->name('patra_challani.store');
Route::get('/patra-challani/{id}/edit', [PatraChallaniController::class, 'edit'])->name('patra_challani.edit');
Route::post('/patra-challani/update/{id}',[PatraChallaniController::class, 'update'])->name('patra_challani.update');
Route::delete('/patra-challani/delete/{id}',[PatraChallaniController::class, 'destroy'])->name('patra_challani.destroy');
//aviyog challani routes
Route::get('/aviyog-challani/{id}/edit', [AviyogChallaniController::class, 'edit'])->name('aviyog_challani.edit');
Route::post('/aviyog-challani/update/{id}',[AviyogChallaniController::class, 'update'])->name('aviyog_challani.update');
Route::delete('/aviyog-challani/delete/{id}',[AviyogChallaniController::class, 'destroy'])->name('aviyog_challani.destroy');

//Punarabedan routes
Route::get('/punarabedan/{id}/edit',[PunarabedanController::class,'edit'])->name('punarabedan.edit');
Route::post('/punarabedan/update/{id}',[PunarabedanController::class, 'update'])->name('punarabedan.update');
Route::delete('/punarabedan/delete/{id}',[PunarabedanController::class, 'destroy'])->name('punarabedan.destroy');
});
Route::prefix('admin')->middleware(['auth'])->group(function() {
    Route::get('/home', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/challani', [ChallaniController::class,'index'])->name('challani.index');
    Route::post('/challani/store', [ChallaniController::class,'storeOrUpdate'])->name('challani.store');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
});
