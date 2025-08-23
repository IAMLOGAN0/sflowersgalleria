<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserOtp;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthOtpApiController extends Controller
{
    /**
     * Generate OTP for mobile number
     */
    public function generateOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        // Check if user exists
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            // Create new user if phone not registered
            $user = User::create([
                'name'     => 'Demo User',           // default name
                'username' => null,                  // optional
                'phone'    => $request->phone,
                'email'    => "example_$request->phone@gmail.com",                  // optional, can add a fake email if needed
                'role'     => 'user',
                'status'   => 'active',
                'password' => bcrypt('12345678')    // default password
            ]);
        }

        $userOtp = UserOtp::where('user_id', $user->id)->latest()->first();
        $now = now();

        if ($userOtp && $now->isBefore($userOtp->expire_at)) {
            $otp = $userOtp->otp;
        } else {
            $otp = rand(123456, 999999);
            $userOtp = UserOtp::create([
                'user_id'   => $user->id,
                'otp'       => $otp,
                'expire_at' => $now->addMinutes(10)
            ]);
        }

        // TODO: Send OTP via SMS here
        // $userOtp->sendSMS($user->phone);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp'     => $otp   // ⚠️ return only for testing, remove in production
        ]);
    }


    /**
     * Verify OTP and return JWT Token
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:users,phone',
            'otp' => 'required|digits:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        $userOtp = UserOtp::where('user_id', $user->id)
                    ->where('otp', $request->otp)
                    ->latest()->first();

        if (!$userOtp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP'], 401);
        }

        if (now()->isAfter($userOtp->expire_at)) {
            return response()->json(['success' => false, 'message' => 'OTP expired'], 401);
        }

        // Expire the OTP after successful use
        $userOtp->update(['expire_at' => now()]);

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Get authenticated user
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Logout user
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
