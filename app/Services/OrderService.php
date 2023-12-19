<?php

namespace App\Services;

use App\Models\Channel;
use App\Models\Conversation;
use App\Models\Format;
use App\Models\Order;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
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

        $totalSum = $this->calculateTotalSum($request->channels);
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
            return 'You don\'t have enough money for this operation';
        }
    }

    private function calculateTotalSum(array $channels): float|int
    {
        $totalSum = 0;
        foreach ($channels as $channel) {
            $totalSum += $this->calculateChannelSum($channel);
        }

        return $totalSum;
    }

    private function calculateChannelSum(array $channel): float|int
    {
        Channel::firstOrFail($channel['id']);
        $formatPrice = $channel[$channel['format']];
        return $formatPrice * $channel['count'];
    }

    /**
     * @throws Exception
     */
    private function createOrderRecord(Request $request, array $channels): void
    {
        foreach ($channels as $channel) {
            $price = $this->calculateChannelSum($channel);
            $formatId = $this->getFormatId($channel);

            $date = $channel['timestamp'];
            \Log::info('before: ' . $date);
            Order::create([
                'user_id' => Auth::id(),
                'description' => $request->description,
                'pattern_id' => $request->pattern_id,
                'post_date' => $date,
                'channel_id' => $channel['id'],
                'format_id' => $formatId,
                'count' => $channel['count'],
                'price' => $price,
            ]);
        }
    }

    private function getFormatId(array $channel): int
    {
        $formatName = self::FORMAT_NAME[$channel['format']];
        $format = Format::where('name', $formatName)->firstOrFail();
        return $format->id;
    }
}
