<?php

namespace App\Http\Controllers\Api;

use App\Constants\PermissionConstant;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request\LoginRequest;
use App\Http\Requests\Request\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private static $GUARD_API = 'api';
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = auth(self::$GUARD_API)->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        $refreshToken = $this->createRefreshToken();
        return $this->respondWithToken($token, $refreshToken);
    }

    /**
     * Register
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'status' => Status::ACTIVE
        ]);
        $user->save();
        $this -> assignRole($user);
        $accessToken = JWTAuth::fromUser($user);
        $refreshToken = $this->createRefreshTokenById($user);
        return $this->respondWithToken($accessToken, $refreshToken);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        try {
            $refreshToken = $request->input('refresh_token');
            $decoded = JWTAuth::getJWTProvider()->decode($refreshToken);
            if (!$decoded || !isset($decoded['user_id']) || !isset($decoded['exp'])) {
                return response()->json(['error' => 'invalid_refresh_token'], 400);
            }
            $userId = $decoded['user_id'];
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            if (time() > $decoded['exp']) {
                return response()->json(['error' => 'refresh_token_expired'], 401);
            }
            $newAccessToken = auth(self::$GUARD_API)->login($user);
            $newRefreshToken = $this->createRefreshToken();

            return $this->respondWithToken($newAccessToken, $newRefreshToken);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'token_invalid'], 401);
        }
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth(self::$GUARD_API)->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth(self::$GUARD_API)->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $refreshToken)
    {
        $ttl = config('jwt.ttl');
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => $ttl * 60
        ]);
    }

    /**
     * Create refresh_token.
     */
    private function createRefreshToken()
    {
        $data = [
            'user_id' =>  auth(self::$GUARD_API)->user()->id,
            'random' => rand() . time(),
            'exp' => time() + config('jwt.refresh_ttl')
        ];
        $refreshToken = JWTAuth::getJWTProvider()->encode($data);
        return $refreshToken;
    }

    /**
     * Create refresh_token.
     */
    private function createRefreshTokenById($user)
    {
        $data = [
            'user_id' => $user->id,
            'random' => rand() . time(),
            'exp' => time() + config('jwt.refresh_ttl')
        ];
        $refreshToken = JWTAuth::getJWTProvider()->encode($data);
        return $refreshToken;
    }

    /**
     * Assign role admin
     */
    private function assignRole ($user){
        $user_Role = Role::where('name', 'admin')->first();
        $user->assignRole($user_Role); 
        $permissions = [
            PermissionConstant::USER_LIST,
            PermissionConstant::USER_VIEW,
            PermissionConstant::USER_CREATE,
            PermissionConstant::USER_DELETE,
            PermissionConstant::USER_UPDATE,
        ];
    
        foreach ($permissions as $permission) {
            if (!$user->hasPermissionTo($permission)) {
                $user->givePermissionTo($permission);
            }
        }
    }
}