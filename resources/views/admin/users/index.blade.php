@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Users</h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-warning text-white fw-bold">
            Add User
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success rounded-3">{{ session('success') }}</div>
    @endif

    <!-- Users Table -->
    <div class="table-responsive shadow-sm rounded-3">
        <table class="table table-hover align-middle">
            <thead class="bg-warning text-dark">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Card Number</th>
                    <th>Expiry Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->mobile_number }}</td>
                    <td>{{ $user->dob }}</td>
                    <td>{{ ucfirst($user->gender) }}</td>
                    <td>{{ $user->card ? $user->card->card_number : '-' }}</td>
                    <td>
                        @if($user->card && $user->card->expiry_date)
                            {{ \Carbon\Carbon::parse($user->card->expiry_date)->format('d M Y') }}
                        @else
                            Not Set
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary fw-bold">Edit</a>

                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger fw-bold">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3 d-flex justify-content-center">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Optional custom styles for table -->
<style>
    .table-hover tbody tr:hover {
        background-color: #fff3cd; /* subtle hover like Yupiter */
    }

    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }

    .btn-primary {
        background-color: #0e2b42;
        border-color: #0e2b42;
    }

    .btn-primary:hover {
        background-color: #0b2235;
        border-color: #0b2235;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #b02a37;
        border-color: #b02a37;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        color: #fff;
    }

    .shadow-sm {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    table {
        border-radius: 12px;
        overflow: hidden;
    }
</style>
@endsection
