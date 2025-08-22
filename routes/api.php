<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// User registration
Route::post('/register', [AuthController::class, 'register']);



// Route::post('/login', [AuthController::class, 'login']);


//Offers API
use App\Http\Controllers\Api\OfferApiController;

Route::get('/offers', [OfferApiController::class, 'index']);




// OTP login only
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
