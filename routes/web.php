<?php

use App\Http\Controllers\Admin\ChannelController;
use App\Http\Controllers\Admin\Controller;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\TelegramController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PatternController;
use App\Http\Controllers\Profile\ChangePasswordController;
use App\Http\Controllers\Profile\NotificationsHistoryController;
use App\Http\Controllers\Profile\NotificationsSettingController;
use App\Http\Controllers\Profile\PersonalDataController;
use App\Http\Controllers\Profile\ReplenishmentController;
use App\Http\Controllers\Profile\TotalBalanceController;
use App\Http\Controllers\Profile\WithdrawController;
use App\Http\Controllers\SuggestedDateController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserChannelController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', fn () => Inertia::render('Customers'))->name('customers');
    Route::get('/owners', fn () => Inertia::render('Owners'))->name('owners');

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

Route::get('/terms-of-service', fn () => inertia('Agreement'))->name('terms-of-service');
Route::get('/rules', fn () => inertia('Rules'))->name('rules');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::post('create-payment-request', [\App\Http\Controllers\Payment\YooKassaController::class, 'createPayment'])->name('create-payment-request');

    Route::prefix('catalog')->name('catalog.')->group(function () {
        Route::resource('channels', \App\Http\Controllers\ChannelController::class);
        Route::get('channels-list', [\App\Http\Controllers\ChannelController::class, 'channelsGet'])->name('channels.list');
        Route::post('favorite-channel', [\App\Http\Controllers\ChannelController::class, 'favorite'])->name('channels.favorite');
        Route::post('order-posts', [\App\Http\Controllers\ChannelController::class, 'orderPosts'])->name('channels.orderPosts');
    });

    Route::get('cart', fn () => inertia('Dashboard/Cart'));

    Route::get('/patterns', [PatternController::class, 'index'])->name('patterns');
    Route::get('/userPatternsPaginated', [PatternController::class, 'userPatternsPaginated'])->name('user-patterns-paginated');
    Route::get('/userPatterns', [PatternController::class, 'userPatterns'])->name('user-patterns');

    Route::group(['prefix' => 'support'], function (){
        Route::get('/', [SupportController::class, 'index'])->name('support');
        Route::post('/get-messages', [SupportController::class, 'getMessagesByTicketId'])->name('get-messages-by-ticket-id');
    });

    Route::get('conversations', [\App\Http\Controllers\ConversationController::class, 'index'])->name('conversations');
    Route::get('conversations-messages/{conversationId}', [\App\Http\Controllers\ConversationController::class, 'conversationsMessages'])->name('conversations.messages');

    Route::group(['prefix' => 'profile'], function () {
        Route::get('personal-data', [PersonalDataController::class, 'index'])->name('personal-data');
        Route::patch('personal-data', [PersonalDataController::class, 'update'])->name('personal-data.store');
        Route::get('total-balance', [TotalBalanceController::class, 'index'])->name('total-balance');
        Route::get('replenishment', [ReplenishmentController::class, 'index'])->name('replenishment');
        Route::get('withdraw', [WithdrawController::class, 'index'])->name('withdraw');
        Route::get('notifications-setting', [NotificationsSettingController::class, 'index'])->name('notifications-setting');

        Route::group(['prefix' => 'notifications'], function () {
            Route::get('/history', [NotificationsHistoryController::class, 'index'])->name('notifications');
            Route::get('/get', [NotificationsHistoryController::class, 'getNotifications'])->name('notifications.get');
            Route::post('/mark-as-read-all', [NotificationsHistoryController::class, 'markAsReadAll'])->name('notifications.mark-as-read-all');
        });


        Route::get('change-password', [ChangePasswordController::class, 'index'])->name('change-password');
        Route::patch('change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');
        Route::post('generate-password', [ChangePasswordController::class, 'generate'])->name('change-password.generate');
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
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/get', [OrderController::class, 'get'])->name('get');
        Route::patch('/{orderItemId}/accept', [OrderController::class, 'acceptOrder'])->name('accept');
        Route::patch('/{orderItemId}/decline', [OrderController::class, 'declineOrder'])->name('decline');
        Route::post('/send-pattern-by-bot', [OrderController::class, 'sendPatternByBot'])->name('send-pattern-by-bot');
    });

    Route::post('to-check-telegram-post', [OrderController::class, 'toCheck'])->name('to-check-telegram-post');
});
Route::middleware(['role:Admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [Controller::class, 'index'])->name('admin');

        Route::get('channels', fn () => inertia('Admin/TablesView'))->name('channels');

        Route::get('support', fn () => inertia('Admin/SupportChatView'))->name('support');

        Route::get('topics', fn () => inertia('Admin/TopicsView'))->name('topics');

        Route::prefix('api')->name('api.')->group(function () {
            Route::apiResource('channels', ChannelController::class);
            Route::apiResource('support', SupportController::class);
        });
    });
});

Route::prefix('admin/api')->name('admin.api.')->group(function () {
    Route::apiResource('topics', TopicController::class);
});
