<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatternRequest;
use App\Http\Requests\UpdatePatternRequest;
use App\Jobs\DuplicatePatternJob;
use App\Models\Pattern;
use App\Models\User;
use App\Services\AvatarService;
use App\Services\MediaItemService;
use Illuminate\Http\JsonResponse;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class PatternController extends Controller
{

    protected AvatarService $avatarService;
    protected MediaItemService $mediaItemService;

    public function __construct(AvatarService $avatarService, MediaItemService $mediaItemService)
    {
        $this->avatarService = $avatarService;
        $this->mediaItemService = $mediaItemService;
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
        return inertia('Dashboard/AddingTemplate', ['patternCount' => $patternCount, 'patternId' => $pattern->id, 'title' => $pattern->title]);
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(UpdatePatternRequest $request, Pattern $pattern): JsonResponse
    {
        $validated = $request->validated();
        $body = $validated['body'] ?? null;
        $title = $validated['title'] ?? null;
        if($body || $title) {
            $pattern->body = $body ?? $pattern->body;
            $pattern->title = $title ?? $pattern->title;
            $pattern->save();
        }

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

        array_walk($mediaData, function($mediaItem, $index) use (&$incomingHashes, &$existingMediaItems, $pattern) {
            $this->mediaItemService->processMediaItem($mediaItem, $index, $existingMediaItems, $pattern, $incomingHashes);
        });

        foreach ($existingMediaItems as $mediaItem) {
            $this->mediaItemService->clearDeprecatedMedia($mediaItem, $mediaData, $incomingHashes);
        }

        $patternMedia = $pattern
            ->getMedia('images')
            ->map(function ($item) {
                return [
                    'url' => $item->getFullUrl(),
                    'thumbnail_path' => $item->getCustomProperty('thumbnail_path'),
                    'order' => $item->getCustomProperty('order')
                ];
            });

        return response()->json($patternMedia->sortBy('order')->values());
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function duplicate(Pattern $pattern): JsonResponse
    {
        if ($pattern->hasVideo()) {
            DuplicatePatternJob::dispatch($pattern);
        } else {
            $duplicatedPattern = $this->copyPattern($pattern);
            $duplicatedPattern->localized_created_at = \App\Services\DateLocalizationService::localize($pattern->created_at);
            return response()->json([$duplicatedPattern, 'fake' => false]);
        }

        return response()->json([$pattern, 'fake' => true]);
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    private function copyPattern(Pattern $pattern): Pattern
    {
        $duplicatedPattern = $pattern->replicate();

        $duplicatedPattern->title = 'Название шаблона (Копия)';
        $duplicatedPattern->save();

        $existingMediaItems = $pattern->getMedia('images');
        foreach ($existingMediaItems as $mediaItem) {
            $order = $mediaItem->getCustomProperty('order');
            $url = $mediaItem->getFullUrl();
            $duplicatedPattern
                ->addMediaFromUrl($url)
                ->withCustomProperties(['order' => $order])
                ->toMediaCollection('images');
        }

        return $duplicatedPattern;
    }

    public function edit(Pattern $pattern)
    {
        $this->authorize('edit', $pattern);

        $patternMedia = $pattern
            ->getMedia('images')
            ->map(function ($item) {
                return [
                    'url' => $item->getFullUrl(),
                    'order' => $item->getCustomProperty('order'),
                    'thumbnail_path' => $item->getCustomProperty('thumbnail_path')
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
        if ($pattern->orders()->exists()){
            return response()->json([
                'success' => false,
                'message' => 'Вы не можете удалить этот шаблон, поскольку на него ссылается заказ.',
            ], 409);
        }

        $pattern->clearMediaCollection();

        $pattern->delete();

        return response()->json(null, 204);
    }
}
