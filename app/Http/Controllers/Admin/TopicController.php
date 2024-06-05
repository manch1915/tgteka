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

    public function pagination(Request $request)
    {
        $query = Topic::query();

        $pageSize = $request->input('pageSize', 15);

        $searchParams = $request->all();

        $searchableFields = [
            'id',
            'title',
        ];

        foreach ($searchableFields as $field) {
            if (isset($searchParams[$field])) {
                $query->where($field, 'LIKE', '%' . $searchParams[$field] . '%');
            }
        }

        $topics = $query->paginate($pageSize);

        return response()->json($topics);
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
        return inertia('Admin/TopicsShow', ['id' => $topic->id, 'title' => $topic->title]);
    }

    public function update(Request $request, Topic $topic)
    {
        $topic->update($request->validate(['title' => 'required|string|max:24']));
        return response()->json($topic);
    }

    public function destroy(Topic $topic)
    {
        try {
            $topic->delete();
            return response()->json(['success' => 'Topic deleted successfully']);
        } catch (\Exception $e) {

            if($e instanceof \Illuminate\Database\QueryException) {

                if($e->getCode() == 23000) {
                    return response()->json([
                        'error' => 'Не удалось удалить тему, так как она используется в одном или нескольких каналах.'
                    ], 403);
                }
            }

            return response()->json(['error' => 'An error occurred while trying to delete the topic.'], 500);
        }
    }
}
