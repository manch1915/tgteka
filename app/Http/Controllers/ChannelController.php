<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/CatalogChannels');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(Channel $channel)
    {
        return inertia('Dashboard/CatalogChannelShow', ['channel' => $channel]);
    }

    public function edit(Channel $channel)
    {
    }

    public function update(Request $request, Channel $channel)
    {
    }

    public function destroy(Channel $channel)
    {
    }
}
