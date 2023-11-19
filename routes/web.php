<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PatternController;
use App\Http\Controllers\Profile\PersonalDataController;
use App\Http\Controllers\Profile\TotalBalanceController;
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

Route::post('/register', [RegisterController::class, 'store'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/terms-of-service', [\App\Http\Controllers\AgreementController::class, 'index'])->name('terms-of-service');
Route::get('/rules', [\App\Http\Controllers\AgreementController::class, 'rules'])->name('rules');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('catalog')->name('catalog.')->group(function () {
        Route::resource('channels', \App\Http\Controllers\ChannelController::class);
    });


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
        Route::get('replenishment', [\App\Http\Controllers\Profile\ReplenishmentController::class, 'index'])->name('replenishment');
        Route::get('withdraw', [\App\Http\Controllers\Profile\WithdrawController::class, 'index'])->name('withdraw');
        Route::get('notifications-setting', [\App\Http\Controllers\Profile\NotificationsSettingController::class, 'index'])->name('notifications-setting');

        Route::get('change-password', [\App\Http\Controllers\Profile\ChangePasswordController::class, 'index'])->name('change-password');
        Route::patch('change-password', [\App\Http\Controllers\Profile\ChangePasswordController::class, 'update'])->name('change-password.update');

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
    Route::get('channelsGet', [\App\Http\Controllers\UserChannelController::class, 'channelsGet'])->name('channels.get');
    Route::get('channels/{channel}', [\App\Http\Controllers\UserChannelController::class, 'edit'])->name('channels.edit');
    Route::patch('channels/{channel}', [\App\Http\Controllers\UserChannelController::class, 'update'])->name('channels.update');
    Route::get('adding-channel', [\App\Http\Controllers\UserChannelController::class, 'show'])->name('adding-channel');
    Route::post('adding-channel', [\App\Http\Controllers\UserChannelController::class, 'store'])->name('adding-channel.store');
});
Route::middleware(['role:Admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\Controller::class, 'index'])->name('admin');

        Route::get('channels', function (){
            return inertia('Admin/TablesView');
        })->name('channels');
        Route::get('support', function (){
            return inertia('Admin/SupportChatView');
        })->name('support');

        Route::prefix('api')->name('api.')->group(function () {
            Route::apiResource('channels', \App\Http\Controllers\Admin\ChannelController::class);
        });
        Route::prefix('api')->name('api.')->group(function () {
            Route::apiResource('support', \App\Http\Controllers\Admin\SupportController::class);
        });

    });
});
