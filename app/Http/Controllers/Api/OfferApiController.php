<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;

class OfferApiController extends Controller
{
    // Fetch all active offers
    public function index()
    {
        $offers = Offer::where('is_active', true)
            ->select('id', 'title', 'image')
            ->get()
            ->map(function ($offer) {
                return [
                    'id' => $offer->id,
                    'title' => $offer->title,
                    'image' => asset($offer->image), // Full URL for mobile app
                ];
            });

        return response()->json([
            'success' => true,
            'offers' => $offers
        ]);
    }
}
