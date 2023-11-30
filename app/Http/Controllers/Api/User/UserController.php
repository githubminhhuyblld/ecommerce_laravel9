<?php

namespace App\Http\Controllers\Api\User;

use App\Constants\Models\BaseEntityManager;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    use BaseEntityManager;

    protected function getModelClass(): string
    {
        return User::class;
    }

    public function __construct()
    {
    }

    /**
     * Select all user and paginate
     * 
     * @param Request $request
     * 
     * @return response the array user
     */
    public function index(Request $request)
    {
        $maxResult = $request->input('max_result', 10);
        $users = User::where('status', Status::ACTIVE)->paginate($maxResult);
        return response()->json($users);
    }

     /**
     * Select user by id
     * 
     * @param int $id
     * 
     * @return response object user
     */
    public function selectById($id)
    {
        $user = User::find($id);
        if (!$user) {
            return Helper::sendNotFoundMessage("User", $id);
        }
        return response()->json($user);
    }

    /**
     * Remove a user and update the status to DELETED.
     *
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function removeUser($id)
    {
        try {
            $user = $this->getModelClass()::find($id);
            if (!$user) {
                return Helper::sendNotFoundMessage("User", $id);
            }
            $this->updateAttribute($id, 'status', Status::DELETED);
            return response()->json(['message' => 'User has been deleted']);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update user information.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function updateUser($id, Request $request)
    {
        try {
            $user = $this->getModelClass()::find($id);
            if (!$user) {
                return Helper::sendNotFoundMessage("User", $id);
            }
            $name = $request->input('name');
            $numberPhone = $request->input('number_phone');
            $this->updateAttribute($id, 'name', $name);
            $this->updateAttribute($id, 'number_phone', $numberPhone);
            return  response()->json(['message' => 'Updated successfully']);;
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
