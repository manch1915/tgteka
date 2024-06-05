<?php

use App\Http\Controllers\Admin\ChannelController;
use App\Http\Controllers\Admin\Controller;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TelegramController;
use App\Http\Controllers\Auth\TwoFactor;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PatternController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\Profile\ChangePasswordController;
use App\Http\Controllers\Profile\NotificationsHistoryController;
use App\Http\Controllers\Profile\NotificationsSettingController;
use App\Http\Controllers\Profile\PersonalDataController;
use App\Http\Controllers\Profile\ReplenishmentController;
use App\Http\Controllers\Profile\TransactionsHistoryController;
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

    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'create'])->name('register');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');

    Route::post('/forgot-password', [LoginController::class, 'forgot'])->name('password.forgot');
    Route::get('/reset-password/{token}', function (string $token) {
        return inertia('PasswordRecover', ['token' => $token]);
    })->name('password.reset')->middleware('validate.password_reset_token');
    Route::post('/reset-password', [LoginController::class, 'update'])->name('password.update');

    Route::post('remember-redirect', [\App\Http\Controllers\UrlController::class, 'rememberURL'])->name('remember-url');

});

Route::middleware(['two.factor'])->group(function () {
    Route::get('/verify-two-factor', fn() => Inertia::render('TwoFactor'))->name('two-factor.index');
    Route::post('/verify-two-factor', [\App\Http\Controllers\Auth\TwoFactor::class, 'verifyTwoFactor'])->name('two-factor.verify');
});

Route::group(['prefix' => 'auth'], function (){
    Route::post('/telegram/callback', [TelegramController::class, 'callback'])->name('telegram.callback');

    Route::get('/vk', function (){
        return Socialite::driver('vkontakte')->redirect();
    })->name('vk-redirect');
    Route::get('/vk/callback', [\App\Http\Controllers\Auth\VkController::class, 'auth']);
});

Route::post('/order/callback', [\App\Http\Controllers\CallbackController::class, 'handleCallback'])->name('order.callback');

Route::middleware(['auth:sanctum', 'two.factor'])->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::post('/enable-two-factor', [TwoFactor::class, 'enableTwoFactor'])->name('two-factor.enable');
    Route::post('/disable-two-factor', [TwoFactor::class, 'disableTwoFactor'])->name('two-factor.disable');

    Route::post('create-payment-request', [\App\Http\Controllers\Payment\FinanceController::class, 'createYooKassaPayment'])->name('create-payment-request');
    Route::post('create-payout-request', [\App\Http\Controllers\Payment\FinanceController::class, 'createPayout'])->name('create-payout-request');

    Route::get('/confirm/{token}', [\App\Http\Controllers\Payment\FinanceController::class, 'confirmPayout'])->name('confirm-payout');

    Route::prefix('catalog')->name('catalog.')->group(function () {
        Route::resource('channels', \App\Http\Controllers\ChannelController::class);
        Route::get('channels-list', [\App\Http\Controllers\ChannelController::class, 'channelsGet'])->name('channels.list');
        Route::get('channel-stats/{channelId}', [\App\Http\Controllers\ChannelController::class, 'fetchChannelStatistics'])->name('channel.stats');
        Route::get('channel-stats-all/{channelId}', [\App\Http\Controllers\ChannelController::class, 'fetchChannelStatisticsAll'])->name('channel.stats.all');
        Route::get('channel-reviews/{channelId}', [\App\Http\Controllers\ChannelController::class, 'fetchChannelReviews'])->name('channel.reviews');
        Route::post('favorite-channel', [\App\Http\Controllers\ChannelController::class, 'favorite'])->name('channels.favorite');
        Route::post('order-posts', [\App\Http\Controllers\ChannelController::class, 'orderPosts'])->name('channels.orderPosts');

        Route::get('channel-orders-count/{channelId}', [\App\Http\Controllers\ChannelController::class, 'fetchChannelOrdersCount'])->name('channel.orders.count');
    });

    Route::get('cart', fn () => inertia('Dashboard/Cart'))->name('cart');

    Route::group(['prefix' => 'placements'], function () {
        Route::get('/', [PlacementController::class, 'index'])->name('placements');
        Route::get('/get', [PlacementController::class, 'get'])->name('placements.get');
        Route::post('/send-report', [PlacementController::class, 'sendReport'])->name('report-send');
        Route::post('/send-review', [PlacementController::class, 'sendReview'])->name('review-send');
        Route::post('/accept-order/{order}', [PlacementController::class, 'acceptOrder'])->name('accept-order');
    });

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
        Route::delete('personal-data', [PersonalDataController::class, 'destroy'])->name('personal-data.destroy');

        Route::get('replenishment', [ReplenishmentController::class, 'index'])->name('replenishment');
        Route::get('withdraw', [WithdrawController::class, 'index'])->name('withdraw');
        Route::get('notifications-setting', [NotificationsSettingController::class, 'index'])->name('notifications-setting');

        Route::group(['prefix' => 'notifications'], function () {
            Route::get('/history', [NotificationsHistoryController::class, 'index'])->name('notifications');
            Route::get('/get', [NotificationsHistoryController::class, 'getNotifications'])->name('notifications.get');
            Route::post('/mark-as-read-all', [NotificationsHistoryController::class, 'markAsReadAll'])->name('notifications.mark-as-read-all');
            Route::get('/get/unread-notifications-count', [NotificationsHistoryController::class, 'unreadNotificationsCount'])->name('get.unread-notifications-count');
        });

        Route::group(['prefix' => 'transactions'], function () {
            Route::get('/history', [TransactionsHistoryController::class, 'index'])->name('transactions');
            Route::get('/get', [TransactionsHistoryController::class, 'getTransactions'])->name('transactions.get');
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
    Route::get('channels/add/channel', [UserChannelController::class, 'show'])->name('adding-channel');
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

Route::middleware(['role:Admin|Moderator'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [Controller::class, 'index'])->name('admin');

        Route::get('channels', fn () => inertia('Admin/TablesView')->withViewData([]))->name('channels');

        Route::get('users', fn () => inertia('Admin/UsersView'))->name('users');

        Route::get('callbacks', fn () => inertia('Admin/CallbacksView'))->name('callbacks');

        Route::get('support', fn () => inertia('Admin/SupportChatView'))->name('support');

        Route::get('topics', fn () => inertia('Admin/TopicsView'))->name('topics');

        Route::get('settings', fn () => inertia('Admin/SettingsView'))->name('settings');

        Route::get('payouts', fn () => inertia('Admin/PayoutsView'))->name('payouts');

        Route::get('reports', fn () => inertia('Admin/ReportsView'))->name('reports');

        Route::prefix('api')->name('api.')->group(function () {
            Route::Resource('channels', ChannelController::class);

            Route::apiResource('support', App\Http\Controllers\Admin\SupportController::class);
            Route::apiResource('users', App\Http\Controllers\Admin\UserController::class);
            Route::apiResource('callbacks', App\Http\Controllers\Admin\CallbackController::class);
            Route::apiResource('settings', App\Http\Controllers\Admin\SettingController::class);

            Route::apiResource('payouts', App\Http\Controllers\Admin\PayoutController::class);
            Route::get('payoutcounts', [App\Http\Controllers\Admin\PayoutController::class, 'countStatuses'])->name('payout.count-statuses');
        });
    });
});

Route::prefix('admin/api')->name('admin.api.')->group(function () {
    Route::Resource('topics', TopicController::class);
    Route::get('list/topics', [TopicController::class, 'pagination'])->name('topics.pagination');
    Route::Resource('reports', \App\Http\Controllers\Admin\OrderReportController::class);
});
