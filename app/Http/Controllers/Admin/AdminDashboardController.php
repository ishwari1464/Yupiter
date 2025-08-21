<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Offer; // ✅ Make sure Offer model exists

class AdminDashboardController extends Controller
{
    // Show admin dashboard
    public function index()
    {
        // Get total counts
        $totalUsers = User::count();
        $totalOffers = Offer::count(); // ✅ Count offers

        // Pass counts to dashboard view
        return view('admin.dashboard', compact('totalUsers', 'totalOffers'));
    }
}
