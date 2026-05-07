<?php

namespace App\Http\Controllers\v1_01;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    public function forgotPasswordApi(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'This email address is not registered.'
            ], 404);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => true,
                'message' => 'Password reset link sent to your email.'
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => __($status)
        ], 500);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'This email address is not registered.'
                ], 200);
            }

            return back()->with('error', 'This email address is not registered.');
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Password reset link sent to your email.'
                ], 200);
            }

            return back()->with('success', 'Password reset link sent to your email.');
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    // ← New: Show reset form
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );
        if ($status === Password::PASSWORD_RESET) {
            \DB::table('password_resets')
                ->where('email', $request->email)
                ->delete();
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Password has been reset successfully.'
                ], 200);
            }

            return redirect()->route('login')
                ->with('success', 'Password has been reset successfully. Please login.');
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
