<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserChannelController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Channels');
    }

    public function channelsGet()
    {
        $channels = auth()->user()->channels()->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($channels);
    }

    public function show()
    {
        return inertia('Dashboard/AddingChannel');
    }

    public function store(StoreChannelRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $path = Storage::putFileAs('public/images', $file, $filename);
            $url = Storage::url($path);
            $validated['avatar'] = $url;
        }

        $validated['user_id'] = auth()->id();

        unset($validated['terms']);
        $channel = Channel::create($validated);

        return to_route('channels');
    }
}
