<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\Profile\PersonalDataController;
use App\Http\Controllers\Profile\TotalBalanceController;
use App\Http\Controllers\PatternController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        return Inertia::render('Customers');
    })->name('customers');
    Route::get('/owners', function () {
        return Inertia::render('Owners');
    })->name('owners');
});

Route::post('/register', [RegisterController::class, 'store'])->name('api-register');
Route::post('/login', [LoginController::class, 'login'])->name('api-login');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/channels-catalog', function () {
        return Inertia::render('Dashboard/ChannelsCatalog');
    })->name('channels-catalog');

    Route::get('/patterns', [PatternController::class, 'index'])->name('patterns');
    Route::get('/patternsGet', [PatternController::class, 'patternsGet'])->name('patterns.get');

    Route::group(['prefix' => 'profile'], function () {
        Route::get('personal-data', [PersonalDataController::class, 'index'])->name('personal-data');
        Route::patch('personal-data', [PersonalDataController::class, 'update'])->name('personal-data.store');
        Route::get('total-balance', [TotalBalanceController::class, 'index'])->name('total-balance');
    });

    Route::get('adding-channel',[ChannelController::class, 'index'])->name('adding-channel');
    Route::get('adding-pattern',[PatternController::class, 'show'])->name('adding-pattern');
    Route::get('edit-pattern/{pattern}',[PatternController::class, 'edit'])->name('edit-pattern');
    Route::patch('adding-pattern/{pattern}',[PatternController::class, 'update'])->name('pattern.update');
    Route::patch('/pattern/{pattern}/rename', [PatternController::class, 'rename'])->name('pattern.rename');
    Route::post('/pattern/{pattern}/duplicate', [PatternController::class, 'duplicate'])->name('pattern.duplicate');
    Route::delete('/pattern/{pattern}', [PatternController::class, 'destroy'])->name('pattern.destroy');
});
