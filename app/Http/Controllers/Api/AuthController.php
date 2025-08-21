<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Card;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;



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
        'password' => ['required', 'confirmed', Password::defaults()],
    ]);

    $user = User::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'mobile_number' => $request->mobile_number,
        'dob' => $request->dob,
        'gender' => $request->gender,
        'password' => Hash::make($request->password),
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

public function login(Request $request)
{
    // 1️⃣ Validate the request
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // 2️⃣ Find user by email
    $user = User::where('email', $request->email)->first();

    // 3️⃣ Check password
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid email or password'
        ], 401);
    }

    // 4️⃣ Generate a new API token
    $token = bin2hex(random_bytes(30)); // 60-character token
    $user->api_token = $token;
    $user->save();

    // 5️⃣ Return response
    return response()->json([
        'message' => 'Login successful',
        'user' => $user,
        'api_token' => $token,
    ], 200);
}

}
