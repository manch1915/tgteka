<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $reviews = Review::all();

        return response()->json($reviews);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // TODO: Add your validation logic here
        $review = Review::create($request->all());

        return response()->json($review, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Review $review
     * @return JsonResponse
     */
    public function show(Review $review)
    {
        return response()->json($review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Review $review
     * @return JsonResponse
     */
    public function update(Request $request, Review $review)
    {
        // TODO: Add your validation logic here
        $review->update($request->all());

        return response()->json($review);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Review $review
     * @return JsonResponse
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json(null, 204);
    }
}
