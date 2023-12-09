<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatternRequest;
use App\Http\Requests\UpdatePatternRequest;
use App\Models\Pattern;
use App\Models\User;
use App\Services\AvatarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(UpdatePatternRequest $request, Pattern $pattern): JsonResponse
    {
        if ($request->hasFile('media')) {
            $pattern
                ->addMedia($request->file('media'))
                ->toMediaCollection('images');
        }else {
            $pattern->clearMediaCollection('images');
        }

        $pattern->update($request->validated());

        return response()->json($pattern);
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
        return inertia('Dashboard/EditTemplate', [
            'patternId' => $pattern->id,
            'patternContent' => $pattern->body,
            'patternMedia' => $this->avatarService->getAvatarUrlOfPattern($pattern),
            'patternName' => $pattern->title,
        ]);
    }

    public function destroy(Pattern $pattern): JsonResponse
    {
        $pattern->delete();

        return response()->json(null, 204);
    }
}
