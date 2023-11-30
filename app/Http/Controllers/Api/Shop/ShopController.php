<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ShopRequest;
use App\Models\Shop;

class ShopController extends Controller
{
    /**
     * Create new shop
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ShopRequest $request)
    {
        $shop = new Shop([
            'name' => $request->input('name'),
            'image' => $request->input('image'),
            'description' => $request->input('description'),
            'address' => $request->input('address'),
        ]);
        $shop->save();
        return response() -> json(['message' => "Create successfully"]);
      
    }
}
