<?php

namespace App\Services;

use App\Models\Pattern;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class MediaItemService
{
    /**
     * @throws FileCannotBeAdded
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function processMediaItem($mediaItem, int $order, $existingMediaItems, Pattern $pattern, array &$incomingHashes): void
    {
        if (is_string($mediaItem)) {
            $this->processUrlMediaItem($mediaItem, $order, $existingMediaItems, $pattern);
        } elseif ($mediaItem instanceof \Illuminate\Http\UploadedFile) {
            $this->processFileMediaItem($mediaItem, $order, $existingMediaItems, $pattern, $incomingHashes);
        }
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    private function processUrlMediaItem(string $url, int $order, $existingMediaItems, Pattern $pattern): void
    {
        $existingImage = $existingMediaItems->first(fn($media) => $url === $media->getFullUrl());

        if (!$existingImage) {
            $pattern
                ->addMediaFromUrl($url)
                ->withCustomProperties(['order' => $order])
                ->toMediaCollection('images');
        } else {
            $existingImage->setCustomProperty('order', $order);
            $existingImage->save();
        }
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    private function processFileMediaItem(\Illuminate\Http\UploadedFile $file, int $order, $existingMediaItems, Pattern $pattern, array &$incomingHashes): void
    {
        $type = $file->getMimeType();
        $hash = $file->hashName();
        $existingImage = $existingMediaItems->first(fn($media) => $hash === $media->getCustomProperty('hash'));

        if (!$existingImage) {
            // Save the original media item first.
            $mediaItem = $pattern
                ->addMedia($file)
                ->withCustomProperties(['hash' => $hash, 'order' => $order])
                ->toMediaCollection('images');

            $incomingHashes[] = $mediaItem->getCustomProperty('hash');

            if ($type == 'video/mp4' || $type == 'video/quicktime') {
                // Generate Video Thumbnail
                $ffmpeg = FFMpeg::create([
                    'ffmpeg.binaries'  => 'C:\ffmpeg\bin\ffmpeg.exe',
                    'ffprobe.binaries' => 'C:\ffmpeg\bin\ffprobe.exe'
                ]);

                $video = $ffmpeg->open($mediaItem->getPath());
                // Get the video duration
                $duration = $video->getFFProbe()->streams($mediaItem->getPath())->videos()->first()->get('duration');

                $timeInSecondsForOneThird = $duration * (1 / 3);

                $frame = $video->frame(TimeCode::fromSeconds($timeInSecondsForOneThird));

                // Save the frame to a temp location
                $tempPath = tempnam(storage_path('app/temp'), 'thumbnail_') . '.jpg';
                $frame->save($tempPath);

                // Add the temp image to the media library associated to the $mediaItem model
                $thumbnailItem = $pattern->addMedia($tempPath)
                    ->usingName('thumbnail')
                    ->toMediaCollection('thumbnails');


                // Save the URL of the thumbnail to the media item
                $mediaItem->setCustomProperty('thumbnail_path', $thumbnailItem->getUrl());

                $mediaItem->save();
            }
        } else {
            $existingImage->setCustomProperty('order', $order);
            $existingImage->save();

            $incomingHashes[] = $existingImage->getCustomProperty('hash');
        }
    }

    public function clearDeprecatedMedia($mediaItem, array $mediaData, array $incomingHashes): void
    {
        foreach ($mediaData as $item) {
            if (is_string($item)) {
                // If the item is a URL and matches the mediaItem's URL, we keep the mediaItem
                if($item === $mediaItem->getFullUrl()){
                    return;
                }
            } elseif ($item instanceof \Illuminate\Http\UploadedFile) {
                // If the item is a file and its hash is in the incomingHashes, we keep the mediaItem
                $mediaHash = $mediaItem->getCustomProperty('hash');
                if($mediaHash && in_array($mediaHash, $incomingHashes)) {
                    return;
                }
            }
        }

        $mediaItem->delete();
    }
}
