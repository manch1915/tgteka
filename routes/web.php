<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\Profile\PersonalDataController;
use App\Http\Controllers\Profile\TotalBalanceController;
use App\Http\Controllers\PatternController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/terms-of-service', [\App\Http\Controllers\AgreementController::class, 'index'])->name('terms-of-service');
Route::get('/rules', [\App\Http\Controllers\AgreementController::class, 'rules'])->name('rules');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/channels-catalog', function () {
        return Inertia::render('Dashboard/ChannelsCatalog');
    })->name('channels-catalog');

    Route::get('/patterns', [PatternController::class, 'index'])->name('patterns');
    Route::get('/patternsGet', [PatternController::class, 'patternsGet'])->name('patterns.get');

    Route::group(['prefix' => 'support'], function (){
        Route::get('/', [\App\Http\Controllers\SupportController::class, 'index'])->name('support');
        Route::post('/get-messages', [\App\Http\Controllers\SupportController::class, 'getMessagesByTicketId'])->name('get-messages-by-ticket-id');
    });

    Route::group(['prefix' => 'profile'], function () {
        Route::get('personal-data', [PersonalDataController::class, 'index'])->name('personal-data');
        Route::patch('personal-data', [PersonalDataController::class, 'update'])->name('personal-data.store');
        Route::get('total-balance', [TotalBalanceController::class, 'index'])->name('total-balance');
    });


    Route::prefix('pattern')->name('pattern.')->group(function () {
        Route::get('adding', [PatternController::class, 'show'])->name('adding');
        Route::get('edit/{pattern}', [PatternController::class, 'edit'])->name('edit');
        Route::patch('adding/{pattern}', [PatternController::class, 'update'])->name('update');
        Route::patch('{pattern}/rename', [PatternController::class, 'rename'])->name('rename');
        Route::post('{pattern}/duplicate', [PatternController::class, 'duplicate'])->name('duplicate');
        Route::delete('{pattern}', [PatternController::class, 'destroy'])->name('destroy');
    });

    Route::get('channels', [\App\Http\Controllers\UserChannelController::class, 'index'])->name('channels');
    Route::get('adding-channel', [\App\Http\Controllers\UserChannelController::class, 'show'])->name('adding-channel');
    Route::post('adding-channel', [\App\Http\Controllers\UserChannelController::class, 'store'])->name('adding-channel.store');
});
