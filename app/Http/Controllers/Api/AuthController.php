<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Card;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    // 📌 Send OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string|min:10|max:15',
        ]);

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

    // 📌 Verify OTP (Login/Register)
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
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP'], 401);
        }

        // Check if user exists or create new
        $user = User::firstOrCreate(
            ['mobile_number' => $request->mobile_number],
            [
                'full_name' => 'User'.rand(1000,9999),
                'email' => 'otp'.rand(1000,9999).'@example.com', // dummy email
                'dob' => '2000-01-01',
                'gender' => 'other',
                'password' => Hash::make(Str::random(8)), // not used
            ]
        );

        // If new user, create Card
        if (!$user->card) {
            do {
                $card_number = mt_rand(1000000000, 9999999999);
            } while (Card::where('card_number', $card_number)->exists());

            Card::create([
                'user_id' => $user->id,
                'card_number' => $card_number,
                'expiry_date' => now()->addYear(),
            ]);
        }

        // Generate token
        $token = Str::random(60);
        $user->api_token = $token;
        $user->save();

        $otpRecord->delete();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'api_token' => $token,
        ]);
    }
}
