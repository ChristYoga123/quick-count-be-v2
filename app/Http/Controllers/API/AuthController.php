<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\AppToken;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('apiAuth', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return ResponseFormatter::error('Email atau password salah', 401);
        }
        // Delete all token from user
        AppToken::where('user_id', auth('api')->user()->id)->delete();
        // Create new token
        AppToken::create([
            'user_id' => auth('api')->user()->id,
            'token' => $token
        ]);
        return ResponseFormatter::success($this->respondWithToken($token), 'Login Sukses');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth('api')->user();
        $user['telepon'] = DB::table('user_credentials')->where('user_id', $user->id)->first()->phone_number ?? null;
        $user['role'] = DB::table('roles')
            ->select('roles.name')
            ->join('model_has_roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $user->id)
            ->first()->name ?? null;
        return ResponseFormatter::success($user, 'Data User Ditemukan');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return ResponseFormatter::success(null, 'Logout Sukses');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return ResponseFormatter::success($this->respondWithToken(auth('api')->refresh()), 'Refresh Token Sukses');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
