<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    // 📌 User Registration (NO password)
    public function register(Request $request)
    {
        $request->validate([
            'full_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'mobile_number' => 'required|string|min:10|max:15|unique:users,mobile_number',
            'dob'           => 'required|date',
            'gender'        => 'required|string|in:male,female,other',
        ]);

        $user = User::create([
            'full_name'     => $request->full_name,
            'email'         => $request->email,
            'mobile_number' => $request->mobile_number,
            'dob'           => $request->dob,
            'gender'        => $request->gender,
            'password'      => null, // 🚫 no password needed
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user'    => $user,
        ]);
    }

    // 📌 Send OTP (only if user is registered)
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string|min:10|max:15',
        ]);

        // Check if user exists
        $user = User::where('mobile_number', $request->mobile_number)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile number not registered. Please register first.',
            ], 404);
        }

        $otp = rand(100000, 999999);

        Otp::create([
            'mobile_number' => $request->mobile_number,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp, // ⚠️ remove in production
        ]);
    }

    // 📌 Verify OTP (Login)
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string|min:10|max:15',
            'otp' => 'required|string',
        ]);

        $otpRecord = Otp::where('mobile_number', $request->mobile_number)
                        ->where('otp', $request->otp)
                        ->where('expires_at', '>=', Carbon::now())
                        ->latest()
                        ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP',
            ], 401);
        }

        // User must already exist
        $user = User::where('mobile_number', $request->mobile_number)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Mobile number not registered. Please register first.',
            ], 404);
        }

        // Generate API token
        $token = Str::random(60);
        $user->api_token = $token;
        $user->save();

        // delete OTP after successful login
        $otpRecord->delete();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'api_token' => $token,
        ]);
    }
}
