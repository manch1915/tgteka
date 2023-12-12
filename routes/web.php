<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\TelegramController;
use App\Http\Controllers\PatternController;
use App\Http\Controllers\Profile\PersonalDataController;
use App\Http\Controllers\Profile\TotalBalanceController;
use App\Http\Controllers\SuggestedDateController;
use App\Http\Controllers\UserChannelController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        return Inertia::render('Customers');
    })->name('customers');
    Route::get('/owners', function () {
        return Inertia::render('Owners');
    })->name('owners');

    Route::post('/register', [RegisterController::class, 'store'])->name('register');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/forgot-password', [LoginController::class, 'forgot'])->name('password.forgot');
    Route::get('/reset-password/{token}', function (string $token) {
        return inertia('PasswordRecover', ['token' => $token]);
    })->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'update'])->name('password.update');
});

Route::group(['prefix' => 'auth'], function (){
    Route::get('/telegram', [TelegramController::class, 'index'])->name('telegram-redirect');
    Route::get('/telegram/callback', [TelegramController::class, 'callback']);
    Route::get('/vk', function (){
        return Socialite::driver('vk')->redirect();
    });
    Route::get('/vk/callback', function (){
        $telegramUser = Socialite::driver('vk')->user();
    });
});

Route::get('/terms-of-service', [\App\Http\Controllers\AgreementController::class, 'index'])->name('terms-of-service');
Route::get('/rules', [\App\Http\Controllers\AgreementController::class, 'rules'])->name('rules');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('catalog')->name('catalog.')->group(function () {
        Route::resource('channels', \App\Http\Controllers\ChannelController::class);
        Route::get('channels-list', [\App\Http\Controllers\ChannelController::class, 'channelsGet'])->name('channels.list');
        Route::post('favorite-channel', [\App\Http\Controllers\ChannelController::class, 'favorite'])->name('channels.favorite');
        Route::post('order-posts', [\App\Http\Controllers\ChannelController::class, 'orderPosts'])->name('channels.orderPosts');
    });

    Route::get('cart', function (){
        return inertia('Dashboard/Cart');
    });

    Route::get('/patterns', [PatternController::class, 'index'])->name('patterns');
    Route::get('/userPatternsPaginated', [PatternController::class, 'userPatternsPaginated'])->name('user-patterns-paginated');
    Route::get('/userPatterns', [PatternController::class, 'userPatterns'])->name('user-patterns');

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
        Route::post('generate-password', [\App\Http\Controllers\Profile\ChangePasswordController::class, 'generate'])->name('change-password.generate');
    });

    Route::get('/suggested-date/accept/{id}/{suggestedDate}', [SuggestedDateController::class, 'accept'])->name('suggested-date.accept');
    Route::get('/suggested-date/decline/{id}', [SuggestedDateController::class, 'decline'])->name('suggested-date.decline');

    Route::prefix('pattern')->name('pattern.')->group(function () {
        Route::get('adding', [PatternController::class, 'show'])->name('adding');
        Route::get('edit/{pattern}', [PatternController::class, 'edit'])->name('edit');
        Route::patch('adding/{pattern}', [PatternController::class, 'update'])->name('update');
        Route::patch('{pattern}/rename', [PatternController::class, 'rename'])->name('rename');
        Route::post('{pattern}/duplicate', [PatternController::class, 'duplicate'])->name('duplicate');
        Route::delete('{pattern}', [PatternController::class, 'destroy'])->name('destroy');
    });

    Route::get('channels', [UserChannelController::class, 'index'])->name('channels');
    Route::get('channelsGet', [UserChannelController::class, 'channelsGet'])->name('channels.get');
    Route::get('channels/{channel}', [UserChannelController::class, 'edit'])->name('channels.edit');
    Route::patch('channels/{channel}', [UserChannelController::class, 'update'])->name('channels.update');
    Route::get('adding-channel', [UserChannelController::class, 'show'])->name('adding-channel');
    Route::post('adding-channel', [UserChannelController::class, 'store'])->name('adding-channel.store');

    Route::prefix('orders')->name('order.')->group(function () {
        Route::get('/', [\App\Http\Controllers\OrderController::class, 'index'])->name('index');
        Route::get('/get', [\App\Http\Controllers\OrderController::class, 'get'])->name('get');
        Route::patch('/{orderItemId}/accept', [\App\Http\Controllers\OrderController::class, 'acceptOrder'])->name('accept');
        Route::patch('/{orderItemId}/decline', [\App\Http\Controllers\OrderController::class, 'declineOrder'])->name('decline');
        Route::post('/send-pattern-by-bot', [\App\Http\Controllers\OrderController::class, 'sendPatternByBot'])->name('send-pattern-by-bot');
    });
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

        Route::get('topics', function (){
            return inertia('Admin/TopicsView');
        })->name('topics');

        Route::prefix('api')->name('api.')->group(function () {
            Route::apiResource('channels', \App\Http\Controllers\Admin\ChannelController::class);
            Route::apiResource('support', \App\Http\Controllers\Admin\SupportController::class);
        });
    });
});

Route::prefix('admin/api')->name('admin.api.')->group(function () {
    Route::apiResource('topics', \App\Http\Controllers\Admin\TopicController::class);
});
