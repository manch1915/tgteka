<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChannelUpdateRequest;
use App\Jobs\FetchChannelStatisticsJob;
use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index(Request $request)
    {
        // Get the 'pageSize' parameter from the request, with a default value of 15 if not provided
        $pageSize = $request->input('pageSize', 15);

        // Get the search parameters
        $searchParams = $request->all();

        // Create a query builder instance
        $query = Channel::with('topic');

        $searchableFields = [
            'channel_name',
            'status',
            'description',
            'language',
            'subscribers_source',
        ];

        foreach ($searchableFields as $field) {
            if (isset($searchParams[$field])) {
                $query->where($field, 'LIKE', '%' . $searchParams[$field] . '%');
            }
        }

        $channels = $query->paginate($pageSize);

        $channels->map(function ($channel) {
            $channel->status = __('messages.' . $channel->status);
            return $channel;
        });

        return response()->json($channels);
    }


    public function store(Request $request)
    {
    }

    public function show(Channel $channel)
    {

    }

    public function edit(Channel $channel)
    {
        return inertia('Admin/TablesShow', ['channel' => $channel->load('user')]);
    }

    public function update(ChannelUpdateRequest $request, Channel $channel)
    {
        $inputData = $request->validated();

        $channel->update($inputData);

        if($channel->status === 'loading'){
            FetchChannelStatisticsJob::dispatch($channel);
        }

        return response()->json();
    }

    public function destroy(Channel $channel)
    {
        $channel->delete();
        return response()->json(null, 204);
    }
}
