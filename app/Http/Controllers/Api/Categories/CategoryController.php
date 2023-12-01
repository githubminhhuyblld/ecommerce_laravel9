<?php

namespace App\Http\Controllers\Api\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryRequest;
use App\Models\Category;


/**
 * @OA\Info(
 *     title="API Documentation for Categories",
 *     version="1.0",
 *     description="API documentation for managing categories."
 * )
 */
class CategoryController extends Controller
{
    /**
     * Create new category
     *
     * @OA\Post(
     *     path="/api/v1/categories",
     *     summary="Create a new category",
     *     tags={"Categories"}, 
     *   @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         description="Bearer token",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="image", type="string"),
     *             @OA\Property(property="description", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Create successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Validation error message")
     *         )
     *     )
     * )
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CategoryRequest $request)
    {
        $category = new Category([
            'name' => $request->input('name'),
            'image' => $request->input('image'),
            'description' => $request->input('description'),
        ]);
        $category->save();
        return response()->json(['message' => "Create successfully"]);
    }
}
