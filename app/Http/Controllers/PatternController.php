<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatternRequest;
use App\Http\Requests\UpdatePatternRequest;
use App\Models\Pattern;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class PatternController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Templates');
    }

    public function patternsGet()
    {
        $patterns = auth()->user()->patterns()->orderBy('created_at', 'desc')->paginate(10);

        foreach ($patterns as $pattern) {
            $pattern->localized_created_at = \App\Services\DateLocalizationService::localize($pattern->created_at);
        }

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
        $validated = $request->validated();

        if($request->hasFile('media')) {
            $file = $request->file('media');

            $pattern
                ->addMedia($file)
                ->toMediaCollection('images');

            unset($validated['media']);
        }

        $pattern->update($validated);

        return response()->json($pattern);
    }

    public function rename(Pattern $pattern, Request $request): JsonResponse
    {
        $pattern->title = $request->title;
        $pattern->save();

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

        // Handle media duplication
        foreach ($pattern->getMedia('images') as $media) {
            $newPattern
                ->addMedia($media->getPath())
                ->toMediaCollection('images');
        }

        $newPattern->localized_created_at = \App\Services\DateLocalizationService::localize($newPattern->created_at);
        return response()->json($newPattern);
    }

    public function edit(Pattern $pattern)
    {
        $patternContent = $pattern->body;
        $patternName = $pattern->title;
        $patternMedia = $pattern->getMedia('images')->last()->getUrl();

        return inertia('Dashboard/EditTemplate', [
            'patternId' => $pattern->id,
            'patternContent' => $patternContent,
            'patternMedia' => $patternMedia,
            'patternName' => $patternName,
        ]);
    }

    public function destroy(Pattern $pattern): JsonResponse
    {
        $pattern->delete();

        return response()->json(null, 204);
    }
}
