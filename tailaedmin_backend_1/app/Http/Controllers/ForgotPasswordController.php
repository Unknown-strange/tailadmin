<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PasswordReset;
use App\Mail\ResetPasswordCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    /**
     * Send verification code to email
     */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        try {
            // Generate 6-digit code
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Delete any existing codes for this email
            PasswordReset::where('email', $request->email)->delete();

            // Store the code
            PasswordReset::create([
                'email' => $request->email,
                'token' => $code,
                'created_at' => now()
            ]);

            // Send email with code
            Mail::to($request->email)->send(new ResetPasswordCode($code, $request->email));

            return response()->json([
                'message' => 'Verification code sent to your email'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to send reset code: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to send verification code. Please try again later.'
            ], 500);
        }
    }

    /**
     * Verify the code
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6'
        ]);

        // Find the reset record
        $reset = PasswordReset::where('email', $request->email)
            ->where('token', $request->code)
            ->first();

        if (!$reset) {
            return response()->json([
                'message' => 'Invalid verification code'
            ], 401);
        }

        // Check if code is expired (valid for 10 minutes)
        if (Carbon::parse($reset->created_at)->addMinutes(10) < now()) {
            $reset->delete();
            return response()->json([
                'message' => 'Verification code has expired. Please request a new one.'
            ], 401);
        }

        // Code is valid
        return response()->json([
            'message' => 'Code verified successfully'
        ], 200);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|min:8'
        ]);

        // Verify code exists and is valid
        $reset = PasswordReset::where('email', $request->email)
            ->where('token', $request->code)
            ->first();

        if (!$reset) {
            return response()->json([
                'message' => 'Invalid verification code'
            ], 401);
        }

        // Check if code is expired
        if ($reset->created_at->addMinutes(10) < now()) {
            $reset->delete();
            return response()->json([
                'message' => 'Verification code has expired'
            ], 401);
        }

        // Check if passwords match
        if ($request->password !== $request->password_confirmation) {
            return response()->json([
                'message' => 'Passwords do not match'
            ], 422);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete the reset token
        $reset->delete();

        // Revoke all tokens for security
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Password reset successfully. Please sign in with your new password.'
        ], 200);
    }
}