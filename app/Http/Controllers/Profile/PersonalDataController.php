<?php

namespace App\Http\Controllers\Profile;

use App\Services\DateLocalizationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\PersonalDataRequest;
use Illuminate\Http\Request;

class PersonalDataController extends Controller
{
    public function index()
    {
        $localizedCreatedAt = DateLocalizationService::localize(auth()->user()->created_at);

        return inertia('Dashboard/Profile/PersonalData', [
            'created_at' => $localizedCreatedAt,
            'user' => auth()->user()
        ]);
    }

    public function update(PersonalDataRequest $request)
    {
        $validated = $request->validated();

        auth()->user()->update($validated);
        return response()->json($request);
    }

    public function destroy(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            return response()->json(['message' => 'Вы не можете удалить пользователя с правами администратора.'], 403);
        }

        $request->session()->invalidate();
        $user->delete();

        return response()->json(['message' => 'Учетная запись пользователя успешно удалена.']);
    }
}
