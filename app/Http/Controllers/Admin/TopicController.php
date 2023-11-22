<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index()
    {
        return Topic::all()->toJson();
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        $topic = Topic::create($request->validate(['title' => 'required|string|max:24']));

        return response()->json($topic);
    }

    public function show(Topic $topic)
    {
    }

    public function edit(Topic $topic)
    {
    }

    public function update(Request $request, Topic $topic)
    {
    }

    public function destroy(Topic $topic)
    {
        return $topic->delete();
    }
}
