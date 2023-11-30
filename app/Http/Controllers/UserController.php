<?php
namespace App\Http\Controllers;

use App\Constants\Models\BaseEntityManager;
use App\Constants\Status;
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
     * Remove a user and update the status to DELETED.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function removeUser($id)
    {
        try {
            $user = $this->getModelClass()::find($id);
            if(!$user){
                return response()->json(['message' => "{$user} not found"], Response::HTTP_NOT_FOUND);
            }
            $this->updateAttribute($id, 'status', Status::DELETED);
            return response()->json(['message' => 'User has been deleted']);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the user'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
