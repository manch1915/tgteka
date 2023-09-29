<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatternRequest;
use App\Models\Pattern;
use Illuminate\Http\JsonResponse;

class PatternController extends Controller
{
    public function index(): JsonResponse
    {
        $patterns = Pattern::all();

        return response()->json($patterns);
    }

    public function store(StorePatternRequest $request): JsonResponse
    {
        $pattern = Pattern::create($request->validated());

        return response()->json($pattern, 201);
    }

    public function show(Pattern $pattern): JsonResponse
    {
        return response()->json($pattern);
    }

    public function update(StorePatternRequest $request, Pattern $pattern): JsonResponse
    {
        $pattern->update($request->validated());

        return response()->json($pattern);
    }

    public function destroy(Pattern $pattern): JsonResponse
    {
        $pattern->delete();

        return response()->json(null, 204);
    }
}
