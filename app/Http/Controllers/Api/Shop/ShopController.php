<?php

namespace App\Http\Controllers\Api\Shop;

use App\Constants\Models\BaseEntityManager;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Requests\Shop\ShopRequest;
use App\Models\Shop;
use Exception;
use Illuminate\Http\Response;

class ShopController extends Controller
{
    use BaseEntityManager;

    protected function getModelClass(): string
    {
        return Shop::class;
    }

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

    
    /**
     * Remove shop by id.
     *
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function removeShop($id)
    {
        try {
            $shop = $this->getModelClass()::find($id);
            if (!$shop) {
                return Helper::sendNotFoundMessage("Shop", $id);
            }
            $this->updateAttribute($id, 'status', Status::DELETED);
            return response()->json(['message' => 'Shop has been deleted']);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
