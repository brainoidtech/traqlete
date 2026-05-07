<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\JwtHelper;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Closure;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token'
            ], 500);
        }

        $user = auth('api')->user();
        $user->load(['userRole', 'userDept']);

        $isCoach = $user->userRole && strtolower($user->userRole->role_name) === 'coach';
    
        if ($isCoach) {
            if ($isCoach) {
                if (!empty($user->document_number)) {
                    $coaches = \App\Models\Coach::with('department')
                        ->where('document', $user->document_number)
                        ->whereNull('deleted_at')
                        ->get();
                    $departments = $coaches->map(function ($coach) {
                        return $coach->department ? [
                            'id'   => $coach->department->dept_id,
                            'name' => $coach->department->dept_name,
                            'coach_id'  => $coach->id,
                        ] : null;
                    })->filter()->unique('id')->values();
                    $department = $departments->isEmpty() ? [] : $departments;
                } else {
                    $department = [];
                }
            }
        } else {
            $department = $user->userDept ? [[
                'id'   => $user->userDept->dept_id,
                'name' => $user->userDept->dept_name
            ]] : [];
        }
        return response()->json([
            'success'    => true,
            'token_type' => 'Bearer',
            'token'      => $token,
            'expires_in' => 60 * 60 * 24,
            'user' => [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'coach_id' => $user->coach_id,
                'role' => $user->userRole ? [
                    'id'   => $user->userRole->id,
                    'name' => $user->userRole->role_name
                ] : null,
                'department' => $department,
            ]
        ]);
    }

    // Refresh token
    public function refreshToken(Request $request)
    {
        try {
            $newToken = auth('api')->refresh();
        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token has expired and cannot be refreshed'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token_type' => 'Bearer',
            'token' => $newToken,
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    // Logout (optional)
    public function logout(Request $request)
    {
        try {
            // Invalidate the current token
            auth('api')->logout();

            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout, please try again'
            ], 500);
        }
    }
}
