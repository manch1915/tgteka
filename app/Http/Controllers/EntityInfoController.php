<?php

namespace App\Http\Controllers;

use App\Models\EntityInfo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EntityInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $entityInfos = EntityInfo::all();

        return response()->json($entityInfos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // TODO: Insert your validation logic here
        $entityInfo = EntityInfo::create($request->all());

        return response()->json($entityInfo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param EntityInfo $entityInfo
     * @return JsonResponse
     */
    public function show(EntityInfo $entityInfo): JsonResponse
    {
        return response()->json($entityInfo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param EntityInfo $entityInfo
     * @return JsonResponse
     */
    public function update(Request $request, EntityInfo $entityInfo): JsonResponse
    {
        // TODO: Insert your validation logic here
        $entityInfo->update($request->all());

        return response()->json($entityInfo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param EntityInfo $entityInfo
     * @return JsonResponse
     */
    public function destroy(EntityInfo $entityInfo): JsonResponse
    {
        $entityInfo->delete();

        return response()->json(null, 204);
    }
}
