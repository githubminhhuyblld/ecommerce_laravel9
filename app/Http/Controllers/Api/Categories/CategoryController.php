<?php

namespace App\Http\Controllers\Api\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Create new category
     *
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
        return response() -> json(['message' => "Create successfully"]);
      
    }
}
