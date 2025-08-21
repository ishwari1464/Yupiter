@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Page Title -->
    <h3 class="mb-4 text-center">Manage Offers</h3>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success rounded-3">{{ session('success') }}</div>
    @endif

    <!-- Add Offer Card -->
    <div class="card shadow-sm rounded-3 mb-4 p-4">
        <h5 class="card-title mb-3">Add New Offer</h5>
        <form action="{{ route('admin.offers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-bold">Offer Title (Optional)</label>
                <input type="text" name="title" class="form-control" placeholder="Enter title">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Offer Image <span class="text-danger">*</span></label>
                <input type="file" name="image" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-warning text-white fw-bold">Upload Offer</button>
        </form>
    </div>

    <!-- Offers List Card -->
    <div class="card shadow-sm rounded-3 p-4">
        <h5 class="card-title mb-3">All Offers</h5>
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-warning">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offers as $offer)
                        <tr>
                            <td>{{ $offer->id }}</td>
                            <td>
                                <img src="{{ asset($offer->image) }}" width="120" class="rounded mb-2">
                                <br>
                                <small>{{ $offer->title ?? 'No Title' }}</small>
                            </td>
                            <td>
                                @if($offer->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.offers.toggle', $offer->id) }}" 
                                   class="btn btn-sm btn-warning mb-1">
                                    {{ $offer->is_active ? 'Deactivate' : 'Activate' }}
                                </a>
                                <form action="{{ route('admin.offers.destroy', $offer->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mb-1"
                                            onclick="return confirm('Delete this offer?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4">No offers found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }

    .table-warning {
        background-color: #ffc107 !important;
        color: #000;
    }

    .table img {
        object-fit: cover;
    }
</style>
@endsection
