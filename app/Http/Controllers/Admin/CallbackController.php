<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CallbackResource;
use App\Models\Callback;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function index()
    {
        $callbacks = Callback::all();

        return CallbackResource::collection($callbacks);
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
