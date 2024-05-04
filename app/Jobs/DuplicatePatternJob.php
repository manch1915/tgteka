<?php

namespace App\Jobs;

use App\Models\Pattern;
use App\Services\MediaItemService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class DuplicatePatternJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Pattern $pattern;

    public function __construct(Pattern $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function handle(MediaItemService $mediaItemService): void
    {
        $newPattern = $this->pattern->replicate();
        $newPattern->title = $newPattern->title . '(Копия)';
        $newPattern->save();

        $existingMediaItems = $this->pattern->getMedia('images');

        foreach ($existingMediaItems as $mediaItem) {
            $order = $mediaItem->getCustomProperty('order');
            $url = $mediaItem->getFullUrl();
            $thumbUrl = $mediaItem->getCustomProperty('thumbnail_path');

            $newMediaItem = $mediaItemService->copyAndProcessUrlMediaItem($url, $order, $newPattern->getMedia('images'), $newPattern);

            if ($mediaItem->mime_type == 'video/mp4' || $mediaItem->mime_type == 'video/quicktime') {
                $newMediaItem->setCustomProperty('thumbnail_path', $thumbUrl);
                $newMediaItem->save();
            }
        }
    }
}
