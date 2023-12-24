<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\PlacementController;
use App\Http\Requests\ReviewRequest;
use App\Models\Order;
use App\Models\Review;
use App\Notifications\ChannelReviewNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Class PlacementControllerTest
 * @package Tests\Http\Controllers
 *
 * The PlacementControllerTest class is responsible for conducting tests against methods of PlacementController class.
 * This test class specifically conducts tests for 'sendReview' method of the PlacementController class.
 * 'sendReview' method creates a review and assigns it to a channel.
 */
class PlacementControllerTest extends TestCase {

    use RefreshDatabase;

    /**
     * Test sendReview method of PlacementController
     *
     * This test asserts that a channel review notification is dispatched when 'sendReview' method is executed
     */
    public function test_send_review_generates_notification()
    {

        $reviewRequest = new ReviewRequest();
        $reviewRequest->replace([
            'order_id' => 1,
            'rating' => 5,
            'review_text' => 'Great Test!'
        ]);

        Order::create([
            'id' => 1,
            'channel_id' => '1',
        ]);

        $placementController = new PlacementController();
        Notification::fake();

        Order::findOrFail(1); // Find the order or fail the test

        // Call sendReview method
        $placementController->sendReview($reviewRequest);

        // Assert that a notification was not sent to the given channel
        Notification::assertSentTo(
            [Review::first()],
            ChannelReviewNotification::class
        );
    }
}
