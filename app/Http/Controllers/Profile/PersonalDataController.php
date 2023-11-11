<?php

namespace App\Http\Controllers\Profile;

use App\Services\DateLocalizationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\PersonalDataRequest;

class PersonalDataController extends Controller
{
    public function index()
    {
        $localizedCreatedAt = DateLocalizationService::localize(auth()->user()->created_at);

        return inertia('Dashboard/Profile/PersonalData', [
            'created_at' => $localizedCreatedAt
        ]);
    }

    public function update(PersonalDataRequest $request)
    {
        $validated = $request->validated();

        auth()->user()->update($validated);
        return response()->json($request);
    }
}
