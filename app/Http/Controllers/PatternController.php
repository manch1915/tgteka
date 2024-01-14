<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatternRequest;
use App\Http\Requests\UpdatePatternRequest;
use App\Models\Pattern;
use App\Models\User;
use App\Services\AvatarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class PatternController extends Controller
{

    protected AvatarService $avatarService;

    public function __construct(AvatarService $avatarService)
    {
        $this->avatarService = $avatarService;
    }

    public function index()
    {
        return inertia('Dashboard/Templates');
    }

    public function userPatternsPaginated()
    {
        $patterns = auth()->user()->patterns()->orderBy('created_at', 'desc')->paginate(10);

        foreach ($patterns as $pattern) {
            $pattern->localized_created_at = \App\Services\DateLocalizationService::localize($pattern->created_at);
        }

        return response()->json($patterns);
    }
    public function userPatterns()
    {
        $patterns = auth()->user()->patterns()->orderBy('created_at', 'desc')->get();

        return response()->json($patterns);
    }

    public function store(StorePatternRequest $request): JsonResponse
    {
        $pattern = Pattern::create($request->validated());

        return response()->json($pattern, 201);
    }

    public function show(User $user)
    {
        $pattern = Pattern::create(
            ['user_id' => auth()->id(),'status' => 'pending']
        );

        $patternCount = auth()->user()->patterns->count();
        return inertia('Dashboard/AddingTemplate', ['patternCount' => $patternCount, 'patternId' => $pattern->id]);
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(UpdatePatternRequest $request, Pattern $pattern): JsonResponse
    {
        $mediaDataUrls = $request->input('media') ?? [];
        $mediaDataFiles = $request->file('media') ?? [];

        $mediaData = array_merge($mediaDataUrls, $mediaDataFiles);


        $existingMediaItems = $pattern->getMedia('images');

        if (!$request->has('media')) {
            foreach ($existingMediaItems as $mediaItem) {
                $mediaItem->delete();
            }
            return response()->json(['message' => 'All media deleted successfully']);
        }


        $incomingHashes = [];

        foreach ($mediaData as $i => $mediaItem) {
            $this->processMediaItem($mediaItem, $i, $existingMediaItems, $pattern, $incomingHashes);
        }
        Log::info('incomingHashes: ' . json_encode($incomingHashes));
        foreach ($existingMediaItems as $mediaItem) {
            $this->clearDeprecatedMedia($mediaItem, $mediaData, $incomingHashes);
        }

        $validated = $request->validated();
        $body = $validated['body'] ?? null;
        if($body) {
            $pattern->body = $body;
            $pattern->save();
        }

        return response()->json(['message' => 'Pattern updated successfully']);
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    private function processMediaItem($mediaItem, int $order, $existingMediaItems, Pattern $pattern, array &$incomingHashes)
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
    private function processUrlMediaItem(string $url, int $order, $existingMediaItems, Pattern $pattern)
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
    private function processFileMediaItem(\Illuminate\Http\UploadedFile $file, int $order, $existingMediaItems, Pattern $pattern, array &$incomingHashes)
    {
        $hash = hash_file('sha256', $file->path());
        $existingImage = $existingMediaItems->first(fn($media) => $hash === $media->getCustomProperty('hash'));

        if (!$existingImage) {
            $mediaItem = $pattern
                ->addMedia($file)
                ->withCustomProperties(['hash' => $hash, 'order' => $order])
                ->toMediaCollection('images');

            $incomingHashes[] = $mediaItem->getCustomProperty('hash');
        } else {
            $existingImage->setCustomProperty('order', $order);
            $existingImage->save();

            $incomingHashes[] = $existingImage->getCustomProperty('hash');
        }
    }



    private function clearDeprecatedMedia($mediaItem, array $mediaData, array $incomingHashes)
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


    public function rename(Pattern $pattern, Request $request): JsonResponse
    {
        $pattern->update(['title' => $request->title]);

        return response()->json($pattern);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function duplicate(Pattern $pattern): JsonResponse
    {
        $newPattern = $pattern->replicate();
        $newPattern->save();

        $pattern->getMedia('images')->each(fn($media) => $newPattern->addMedia($media->getPath())->toMediaCollection('images'));

        $newPattern->localized_created_at = \App\Services\DateLocalizationService::localize($newPattern->created_at);

        return response()->json($newPattern);
    }

    public function edit(Pattern $pattern)
    {
        $patternMedia = $pattern
            ->getMedia('images')
            ->map(function ($item) {
                return [
                    'url' => $item->getFullUrl(),
                    'order' => $item->getCustomProperty('order')
                ];
            });

        return inertia('Dashboard/EditTemplate', [
            'patternId' => $pattern->id,
            'patternContent' => $pattern->body,
            'patternMedia' => $patternMedia->sortBy('order')->values(),
            'patternName' => $pattern->title,
        ]);
    }

    public function destroy(Pattern $pattern): JsonResponse
    {
        $pattern->delete();

        return response()->json(null, 204);
    }
}
