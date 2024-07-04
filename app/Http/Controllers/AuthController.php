<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * @bodyParam email string required Email of user, example admin@admin.com
     * @bodyParam password string The password of user
     * @bodyParam name string The name of user
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = User::create($validated);
        $token = Auth::login($user);

        return $this->respondWithToken($token);
    }

    /**
     * @bodyParam email string required Email of user, example admin@admin.com
     * @bodyParam password string The password of user
     */
    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @authenticated
     * @headers {
     *       "Authorization": "Bearer {token}"
     *  }
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * @authenticated
     * @headers {
     *       "Authorization": "Bearer {token}"
     *  }
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @authenticated
     * @headers {
     *       "Authorization": "Bearer {token}"
     *  }
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}
