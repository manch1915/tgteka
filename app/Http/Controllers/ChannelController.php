<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChannelController extends Controller
{

    public function index()
    {
        return inertia('Dashboard/AddingChannel');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // TODO: Insert your validation logic here
        $channel = Channel::create($request->all());

        return response()->json($channel, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Channel $channel
     * @return JsonResponse
     */
    public function show(Channel $channel)
    {
        return response()->json($channel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Channel $channel
     * @return JsonResponse
     */
    public function update(Request $request, Channel $channel)
    {
        // TODO: Insert your validation logic here
        $channel->update($request->all());

        return response()->json($channel);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Channel $channel
     * @return JsonResponse
     */
    public function destroy(Channel $channel)
    {
        $channel->delete();

        return response()->json(null, 204);
    }
}
