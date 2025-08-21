<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Body & Font */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }

        /* Navbar */
        .navbar {
            background-color: #0e2b42; /* Yupiter blue */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 10px 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .navbar .logo {
            height: 50px;
        }

        .navbar .btn {
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #000;
            transition: 0.3s;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            color: #fff;
        }

        .btn-danger {
            transition: 0.3s;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Sidebar */
        .sidebar {
            background-color: #0e2b42;
            color: #fff;
            min-width: 220px;
            height: 100vh;
            padding-top: 80px; /* space for navbar */
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: #fff;
            padding: 14px 20px;
            font-weight: 500;
            transition: 0.3s;
            border-radius: 8px;
            margin: 4px 8px;
        }
        .sidebar .nav-link:hover {
            background-color: #ffc107;
            color: #000;
        }
        .sidebar .nav-link.active {
            background-color: #ffc107;
            color: #000;
        }

        /* Content */
        .content {
            margin-left: 220px;
            padding: 100px 30px 30px;
            width: calc(100% - 220px);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                min-width: 180px;
            }
            .content {
                margin-left: 180px;
                padding: 90px 20px 20px;
            }
            .navbar .logo {
                height: 40px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar d-flex justify-content-between align-items-center">
    <!-- Logo -->
    <div>
        <img src="{{ asset('images/yupiterlogo.png') }}" alt="Logo" class="logo">
    </div>

    <!-- Buttons -->
    <div class="d-flex gap-2">
        <!-- Back Button -->
        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning">Back</a>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                Users
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/offers*') ? 'active' : '' }}" href="{{ route('admin.offers.index') }}">
                Offers
            </a>
        </li>
    </ul>
</div>

<!-- Page Content -->
<div class="content">
    @yield('content')
</div>

</body>
</html>
