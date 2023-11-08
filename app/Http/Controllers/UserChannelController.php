<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChannelRequest;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserChannelController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Channels');
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
        $channel = Channel::create($validated);

        return redirect()->route('channels', $channel);
    }
}
