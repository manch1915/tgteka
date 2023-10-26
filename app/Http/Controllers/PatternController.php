<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatternRequest;
use App\Http\Requests\UpdatePatternRequest;
use App\Models\Pattern;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PatternController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Templates');
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

    public function destroy(Pattern $pattern): JsonResponse
    {
        $pattern->delete();

        return response()->json(null, 204);
    }
}
