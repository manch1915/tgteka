<?php

namespace App\Services;

use App\Jobs\UpdateFinishedOrdersJob;
use App\Models\Channel;
use App\Models\Conversation;
use App\Models\Format;
use App\Models\Order;
use App\Notifications\OrderCreatedNotification;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    const FORMAT_NAME = [
        'format_one_price' => '1/24',
        'format_two_price' => '2/48',
        'format_three_price' => '3/72',
        'no_deletion_price' => 'no_deletion'
    ];

    /**
     * @throws Exception
     */
    public function createOrder(Request $request): ?string
    {
        if (!$request->has('channels')) {
            return 'Invalid Request. Channels not provided.';
        }

        $channels = $request->channels;
        $totalSum = $this->calculateTotalSum($channels);;

        $user = Auth::user();

        $balanceCheck = $this->checkBalanceAndExecuteTransaction($user, $totalSum);

        if ($balanceCheck !== null) {
            return $balanceCheck;
        }

        $this->createOrderRecord($request, $request->channels);

        $uniqueUserIds = collect($request->channels)->pluck('user_id')->unique()->toArray();

        foreach ($uniqueUserIds as $userTwoId) {
            $existingConversation = Conversation::where(function ($query) use ($user, $userTwoId) {
                $query->where('user_one', $user->id)->where('user_two', $userTwoId);
            })->orWhere(function ($query) use ($user, $userTwoId) {
                $query->where('user_one', $userTwoId)->where('user_two', $user->id);
            })->first();

            if (!$existingConversation) {
                Conversation::create([
                    'user_one' => $user->id,
                    'user_two' => $userTwoId,
                ]);
            }
        }

        return null;
    }

    private function checkBalanceAndExecuteTransaction($user, $totalSum): ?string
    {
        if ($user->balance >= $totalSum) {
            $user->decrementBalance($totalSum);
            return null;
        } else {
            return 'У вас недостаточно денег на эту операцию';
        }
    }

    /**
     * @throws Exception
     */
    private function calculateTotalSum(array $channels): float|int
    {
        $totalSum = 0;
        foreach ($channels as $channel) {
            try {
                $totalSum += $this->calculateChannelSum($channel);
            } catch (Exception $e) {
                logger()->error($e);
            }
        }

        return $totalSum;
    }

    /**
     * @throws Exception
     */
    private function calculateChannelSum(array $channel): float|int
    {
        if (!isset($channel['id'])) {
            throw new Exception('Invalid channel id provided.');
        }

        Channel::findOrFail($channel['id']);
        return $channel[$channel['format']] ?? 0;
    }

    /**
     * @throws Exception
     */
    private function createOrderRecord(Request $request, array $channels): void
    {
        foreach ($channels as $channel) {
            $price = $this->calculateChannelSum($channel);
            $formatDetails = $this->getFormatDetails($channel);

            if (!isset($channel['timestamp'])) {
                throw new Exception('Invalid timestamp provided.');
            }

            // Add days to post_date
            $postDate = new DateTime($channel['timestamp']);
            $postDateEnd = $postDate->modify('+' . $formatDetails['days'] . ' day');

            $order = Order::create([
                'user_id' => Auth::id(),
                'description' => $request->description,
                'pattern_id' => $request->pattern_id,
                'post_date' => $channel['timestamp'],
                'post_date_end' => $postDateEnd,
                'channel_id' => $channel['id'],
                'format_id' => $formatDetails['id'],
                'price' => $price,
            ]);

            $channelModel = Channel::find($channel['id']);

            if ($channelModel && $channelModel->user) {
                $channelModel->user->notify(new OrderCreatedNotification($order));
            }

            $postDateEndCarbon = Carbon::instance($postDateEnd);
            $delay = $postDateEndCarbon->diffInSeconds(Carbon::now()) + 400;

            logger()->info("Delay calculated for the order {$order->id} is {$delay} seconds");
            UpdateFinishedOrdersJob::dispatch($order)->delay($delay);
        }
    }

    /**
     * @throws Exception
     */
    private function getFormatDetails(array $channel): array
    {
        if (!isset($channel['format']) || !isset(self::FORMAT_NAME[$channel['format']])) {
            throw new Exception('Invalid format provided.');
        }

        $formatName = self::FORMAT_NAME[$channel['format']];

        // Check if format name contains '/'
        if (!str_contains($formatName, '/')) {
            throw new Exception('Invalid format name. It should contain "/".');
        }

        $format = Format::where('name', $formatName)->firstOrFail();

        $formatParts = explode('/', $formatName);
        $days = $formatParts[0];

        return ['id' => $format->id, 'days' => $days];
    }


}
