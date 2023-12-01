<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Requests\Product\VariantRequest;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Variant;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Create new product 
     * 
     * @param ProductRequest $request
     * 
     * @return response message "Created successfully"
     */
    public function create(ProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $product = new Product([
                'name'        => $request->input('name'),
                'description' => $request->input('description'),
                'price'       => $request->input('price'),
                'quantity'    => $request->input('quantity'),
                'sale'        => $request->input('sale'),
                'old_price'   => $request->input('old_price'),
                'new_price'   => $request->input('new_price'),
                'image'       => $request->input('image'),
                'color'       => $request->input('color'),
                'size'        => $request->input('size'),
            ]);
            $product->save();
            $categoryIds = $request->input('categories', []);
            $product->categories()->sync($categoryIds);
            $shopId = $request->input('shop_id');
            $shop = Shop::findOrFail($shopId);
            $shop->products()->save($product);
            DB::commit();
            return response()->json(['message' => "Create successfully"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Failed to create product: " . $e->getMessage()], 500);
        }
    }

    /**
     * Select product by id
     * 
     * @param int $id
     * 
     * @return response object product
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return Helper::sendNotFoundMessage("Product", $id);
        }

        return response()->json(['data' => $product], Response::HTTP_OK);
    }

    /**
     * Select product by category id
     * 
     * @param int $id
     * 
     * @return response object product
     */
    public function getProductsByCategory($categoryId)
    {
        $products = Product::whereHas('categories', function ($query) use ($categoryId) {
            $query->where('categories.id', $categoryId);
        })->get();

        return response()->json($products);
    }

    /**
     * Select product by shop id
     * 
     * @param int $id
     * 
     * @return response object product
     */
    public function getProductsByShopId($shopId)
    {
        $shop = Shop::find($shopId);

        if (!$shop) {
            return Helper::sendNotFoundMessage('Shop', $shopId);
        }
        $products = Product::where('shop_id', $shopId)->get();
        return response()->json($products);
    }

    /**
     * Create new variant by product id
     * 
     * @param VariantRequest $request
     * @param int $id
     * 
     * @return response message "Created successfully"
     */
    public function addVariant(VariantRequest $request, $productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return Helper::sendNotFoundMessage('Product', $productId);
        }
        $variant = new Variant([
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'price'       => $request->input('price'),
            'quantity'    => $request->input('quantity'),
            'sale'        => $request->input('sale'),
            'old_price'   => $request->input('old_price'),
            'new_price'   => $request->input('new_price'),
            'image'       => $request->input('image'),
            'color'       => $request->input('color'),
            'size'        => $request->input('size'),
        ]);

        $product->variants()->save($variant);
        return response()->json(['message' => 'Created successfully']);
    }
}
