<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Callback;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 15);

        $searchParams = $request->all();

        // Create a query builder instance
        $query = Callback::all()->toQuery();

        $searchableFields = [
            'id',
            'name',
            'email',
        ];

        foreach ($searchableFields as $field) {
            if (isset($searchParams[$field])) {
                $query->where($field, 'LIKE', '%' . $searchParams[$field] . '%');
            }
        }

        $callbacks = $query->paginate($pageSize);

        return response()->json($callbacks);
    }

    public function store(Request $request)
    {
    }

    public function show(Callback $callback)
    {
    }

    public function update(Request $request, Callback $callback)
    {
        $status = $request->input('status');

        $callback->status = $status;
        $callback->save();

        return response()->json(['message' => 'Статус успешно обновлен.']);
    }

    public function destroy(Callback $callback)
    {
    }
}
