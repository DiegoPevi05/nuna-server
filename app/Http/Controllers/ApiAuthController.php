<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Log;

class ApiAuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        Auth::logout();
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        if (auth()->check()) {
            return response()->json(auth()->refresh());
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {

        if (auth()->check()) {
            $user = auth()->user();
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ];
            return response()->json($userData);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
