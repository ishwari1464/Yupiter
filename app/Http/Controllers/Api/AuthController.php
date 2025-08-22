<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Card;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required|string|unique:users,mobile_number',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female,other',
            // Removed password validation
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'dob' => $request->dob,
            'gender' => $request->gender,
            // No password
        ]);

        // ✅ Generate unique 10-digit card number
        do {
            $card_number = mt_rand(1000000000, 9999999999);
        } while (Card::where('card_number', $card_number)->exists());

        // Create Card with expiry date = 1 year from now
        $card = Card::create([
            'user_id' => $user->id,
            'card_number' => $card_number,
            'expiry_date' => now()->addYear(), // set 1 year validity
        ]);

        // Return expiry date in response
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'card_number' => $card->card_number,
            'expiry_date' => $card->expiry_date->toDateString(),
        ], 201);
    }

    // Step 1: Request OTP
    public function requestOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // Send OTP via email (or SMS)
        // Mail::to($user->email)->send(new OtpMail($otp)); // Implement OtpMail if needed

        // For demo, return OTP in response (remove in production)
        return response()->json([
            'message' => 'OTP sent to your email',
            'otp' => $otp, // Remove this in production!
        ]);
    }

    // Step 2: Verify OTP and login
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (
            !$user ||
            $user->otp_code != $request->otp ||
            Carbon::now()->gt($user->otp_expires_at)
        ) {
            return response()->json([
                'message' => 'Invalid or expired OTP'
            ], 401);
        }

        // OTP is valid, clear OTP fields
        $user->otp_code = null;
        $user->otp_expires_at = null;

        // Generate API token
        $token = bin2hex(random_bytes(30));
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'api_token' => $token,
        ], 200);
    }
}