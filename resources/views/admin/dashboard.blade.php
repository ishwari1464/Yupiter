<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #f1f4f8;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Navbar */
    .navbar {
        background-color: #0e2b42; /* Yupiter Blue */
        padding: 0.8rem 2rem;
    }
    .navbar .logo {
        height: 50px;
    }

    /* Dashboard Welcome */
    .dashboard-welcome {
        margin: 90px 0 30px 0;
        text-align: center;
        font-size: 1.4rem;
        font-weight: 600;
        color: #0e2b42;
    }

    /* Dashboard cards */
    .dashboard-card {
        width: 260px;
        height: 260px;
        border-radius: 18px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: #fff;
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin: 15px auto;
    }
    .dashboard-card:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    /* Card gradients */
    .bg-users {
        background: linear-gradient(135deg, #ffc107, #ffdb4d);
        color: #000;
    }
    .bg-offers {
        background: linear-gradient(135deg, #0e2b42, #196d9c);
    }

    /* Card texts */
    .dashboard-card h5 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .dashboard-card h2 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .dashboard-card a.btn {
        font-size: 0.9rem;
        font-weight: 600;
        padding: 6px 14px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-card {
            width: 200px;
            height: 200px;
        }
        .dashboard-card h2 {
            font-size: 2rem;
        }
        .dashboard-card h5 {
            font-size: 1rem;
        }
        .dashboard-welcome {
            font-size: 1.2rem;
        }
    }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg d-flex justify-content-between align-items-center">
    <!-- Logo on the left -->
    <div>
        <img src="{{ asset('images/yupiterlogo.png') }}" alt="Logo" class="logo">
    </div>

    <!-- Logout button on the right -->
    <div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</nav>

<!-- Dashboard Welcome Message -->
<div class="dashboard-welcome">
    Welcome, {{ auth()->guard('admin')->user()->name }}
</div>

<!-- Dashboard Content -->
<div class="container mt-4">
    <div class="row justify-content-center">

        <!-- Total Users Card -->
        <div class="col-auto">
            <div class="dashboard-card bg-users">
                <h5>Total Users</h5>
                <h2>{{ $totalUsers }}</h2>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light">Manage</a>
            </div>
        </div>

        <!-- Total Offers Card -->
        <div class="col-auto">
            <div class="dashboard-card bg-offers">
                <h5>Total Offers</h5>
                <h2>{{ $totalOffers }}</h2>
                <a href="{{ route('admin.offers.index') }}" class="btn btn-light">Manage</a>
            </div>
        </div>

    </div>
</div>

</body>
</html>
