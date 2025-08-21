@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Card Container -->
    <div class="card shadow-sm rounded-3 p-4">
        <!-- Page Title -->
        <h3 class="mb-4 text-center">Edit User</h3>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success rounded-3">{{ session('success') }}</div>
        @endif

        <!-- Edit Form -->
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Full Name</label>
                <input type="text" name="full_name" class="form-control" value="{{ $user->full_name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mobile Number</label>
                <input type="text" name="mobile_number" class="form-control" value="{{ $user->mobile_number }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="{{ $user->dob }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="male" @if($user->gender=='male') selected @endif>Male</option>
                    <option value="female" @if($user->gender=='female') selected @endif>Female</option>
                    <option value="other" @if($user->gender=='other') selected @endif>Other</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary fw-bold">Cancel</a>
                <button type="submit" class="btn btn-warning text-white fw-bold">Update User</button>
            </div>
        </form>
    </div>
</div>

<!-- Optional Styles -->
<style>
    .card {
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }

    .btn-warning {
        background-color: #ffc107;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        color: #fff;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        color: #fff;
    }
</style>
@endsection
