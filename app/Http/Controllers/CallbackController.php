<?php

namespace App\Http\Controllers;

use App\Http\Requests\CallbackRequest;
use App\Models\Callback;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function handleCallback(CallbackRequest $request)
    {
        $validated = $request->validated();

        unset($validated['terms']);

        Callback::create($validated);

        return response()->json(['message' => 'Callback created successfully'], 201);
    }
}
