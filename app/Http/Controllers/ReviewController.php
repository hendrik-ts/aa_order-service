<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * @OA\Tag(
     *     name="Reviews",
     *     description="API Endpoints for managing reviews"
     * )
     */
    /**
     * @OA\Get(
     *     path="/api/reviews",
     *     tags={"Reviews"},
     *     summary="List reviews with pagination",
     *    @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of reviews"
     *     )
     * )
     */
    public function index()
    {
        return Review::orderBy('rating', 'desc')->paginate(5);
    }

    /**
     * @OA\Post(
     *     path="/api/reviews",
     *     tags={"Reviews"},
     *     summary="Create a new review",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","name","rating"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="comment", type="string", example="Great service!"),
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Review created"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'name' => 'required|string',
            'comment' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = Review::create([
            'email' => $validated['email'],
            'name' => $validated['name'],
            'comment' => $validated['comment'],
            'rating' => $validated['rating'],
        ]);

        return response()->json($review, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/reviews/{id}",
     *     tags={"Reviews"},
     *     summary="Get a single review",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Review ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review found"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Review not found"
     *     )
     * )
     */
    public function show($id)
    {
        return Review::findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/api/reviews/{id}",
     *     tags={"Reviews"},
     *     summary="Update a review",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Review ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rating"},
     *             @OA\Property(property="comment", type="string", example="Updated comment"),
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=4)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review updated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Review not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $validated = $request->validate([
            'comment' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review->update($validated);

        return response()->json($review);
    }

    /**
     * @OA\Delete(
     *     path="/api/reviews/{id}",
     *     tags={"Reviews"},
     *     summary="Delete a review",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Review ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Review not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        $review->delete();

        return response()->json(['message' => 'Review deleted']);
    }
}
