<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CallbackResource;
use App\Models\Callback;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();

        return response()->json($settings);
    }

    public function store(Request $request)
    {

    }

    public function show(Setting $setting)
    {

    }

    public function update(Request $request, Setting $setting)
    {
        $data = $request->validate([
            'key' => 'required',
            'value' => 'required'
        ]);

        Setting::updateOrCreate(
            ['key' => 'replenishment_min_amount'],
            ['value' => $data['value']]
        );

        return back()->with('status', 'Successfully updated the setting.');
    }

    public function destroy(Callback $callback)
    {
    }
}
