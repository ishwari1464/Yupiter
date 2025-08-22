<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Card;
use App\Models\Otp;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Registration
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required|string|unique:users,mobile_number',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female,other',
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'dob' => $request->dob,
            'gender' => $request->gender,
        ]);

        // Generate unique 10-digit card number
        do {
            $card_number = mt_rand(1000000000, 9999999999);
        } while (Card::where('card_number', $card_number)->exists());

        $card = Card::create([
            'user_id' => $user->id,
            'card_number' => $card_number,
            'expiry_date' => now()->addYear(),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'card_number' => $card->card_number,
            'expiry_date' => $card->expiry_date->toDateString(),
        ], 201);
    }

    // Step 1: Send OTP (temporary, via mobile number)
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
        ]);

        $otp = rand(100000, 999999);
        $expires_at = Carbon::now()->addMinutes(5);

        // Save OTP in otps table (temporary)
        Otp::updateOrCreate(
            ['mobile_number' => $request->mobile_number],
            ['otp' => $otp, 'expires_at' => $expires_at]
        );

        // Return OTP in response for testing
        return response()->json([
            'message' => 'OTP generated',
            'otp' => $otp,
        ]);
    }

    // Step 2: Verify OTP and login
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'otp' => 'required|digits:6',
        ]);

        $otpData = Otp::where('mobile_number', $request->mobile_number)
                      ->where('otp', $request->otp)
                      ->first();

        if (!$otpData || Carbon::now()->gt($otpData->expires_at)) {
            return response()->json([
                'message' => 'Invalid or expired OTP'
            ], 401);
        }

        // OTP valid → delete it
        $otpData->delete();

        // Get or create user by mobile number
        $user = User::firstOrCreate(
            ['mobile_number' => $request->mobile_number],
            ['full_name' => 'Test User', 'email' => $request->mobile_number.'@test.com']
        );

        // Generate API token
        $token = bin2hex(random_bytes(30));
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'api_token' => $token,
        ]);
    }
}
