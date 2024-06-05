<?php

namespace App\Jobs;

use App\Models\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchAllChannelStatisticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $chunkSize = 200;

        Channel::where('status', 'accepted')->chunk($chunkSize, function ($channels) {
            foreach ($channels as $channel) {
                try {
                    dispatch(new FetchChannelStatisticsJob($channel));
                } catch (\Throwable $exception) {

                }
            }
        });

    }
}
