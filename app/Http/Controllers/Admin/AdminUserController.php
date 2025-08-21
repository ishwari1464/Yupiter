<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Card;
class AdminUserController extends Controller
{
    // Show all users
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Show form to create new user
    public function create()
    {
        return view('admin.users.create');
    }

    // Store new user
   
public function store(Request $request)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'mobile_number' => 'required|unique:users,mobile_number',
        'dob' => 'required|date',
        'gender' => 'required|in:male,female,other',
        'password' => 'required|confirmed|min:6',
    ]);

    $user = User::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'mobile_number' => $request->mobile_number,
        'dob' => $request->dob,
        'gender' => $request->gender,
        'password' => bcrypt($request->password),
    ]);

    // Create card with 1 year expiry
    Card::create([
        'user_id' => $user->id,
        'card_number' => 'CARD-' . strtoupper(uniqid()),
        'expiry_date' => now()->addYear(),
    ]);

    return redirect()->route('admin.users.index')->with('success', 'User created successfully with a card valid for 1 year');
}


    // Show form to edit user
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile_number' => 'required|unique:users,mobile_number,' . $user->id,
            'dob' => 'required|date',
            'gender' => 'required|in:male,female,other',
        ]);

        $user->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'dob' => $request->dob,
            'gender' => $request->gender,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}
