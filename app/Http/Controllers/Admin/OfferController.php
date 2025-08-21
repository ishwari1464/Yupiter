<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offer;

class OfferController extends Controller
{
    // Show all offers in admin panel
    public function index()
    {
        $offers = Offer::latest()->get();
        return view('admin.offers.index', compact('offers'));
    }

    // Store new offer
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // upload image
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('offers'), $imageName);

        Offer::create([
            'title' => $request->title,
            'image' => 'offers/'.$imageName,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Offer created successfully');
    }

    // Toggle active/inactive
    public function toggle($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->is_active = !$offer->is_active;
        $offer->save();

        return redirect()->back()->with('success', 'Offer status updated');
    }

    // Delete offer
    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);

        // delete image
        if (file_exists(public_path($offer->image))) {
            unlink(public_path($offer->image));
        }

        $offer->delete();
        return redirect()->back()->with('success', 'Offer deleted successfully');
    }
}
