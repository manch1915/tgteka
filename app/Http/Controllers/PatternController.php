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

class PatternController extends Controller
{
    public function index()
    {
        $patterns = auth()->user()->patterns;

        foreach ($patterns as $pattern) {
            $pattern->localized_created_at = \App\Services\DateLocalizationService::localize($pattern->created_at);
        }

        return inertia('Dashboard/Templates', ['patterns' => $patterns]);
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

    public function update(UpdatePatternRequest $request, Pattern $pattern): JsonResponse
    {
        $validated = $request->validated();

        if($request->hasFile('media')) {
            $file = $request->file('media');
            $filename = $file->getClientOriginalName();
            $path = Storage::putFileAs('images', $file, $filename);
            $url = Storage::url($path);
            $validated['media'] = $url;
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

    public function duplicate(Pattern $pattern): JsonResponse
    {
        $newPattern = $pattern->replicate();
        $newPattern->save();
        $newPattern->localized_created_at = \App\Services\DateLocalizationService::localize($newPattern->created_at);
        return response()->json($newPattern);
    }

    public function destroy(Pattern $pattern): JsonResponse
    {
        $pattern->delete();

        return response()->json(null, 204);
    }
}
