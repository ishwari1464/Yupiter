<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Body Background */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Login Card */
        .login-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.15);
            padding: 40px 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }
        /* .login-card:hover {
            transform: translateY(-5px);
        } */

        /* Logo */
        .login-logo {
            width: 120px;
            margin-bottom: 20px;
        }

        /* Form Inputs */
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease-in-out;
        }
        .form-control:focus {
            border-color: #0e2b42;
            box-shadow: 0 0 8px rgba(14,43,66,0.3);
        }

        /* Button */
        .btn-login {
            background-color: #0e2b42;
            color: #ffc107;
            border-radius: 8px;
            font-weight: 600;
            padding: 10px;
            transition: background 0.3s ease-in-out;
        }
        .btn-login:hover {
            background-color: #ffc107;
            color: #0e2b42;
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            padding: 10px 15px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <!-- Logo -->
        <img src="{{ asset('images/yupiterlogo.png') }}" alt="Logo" class="login-logo">

        <!-- Title -->
        <h3 class="mb-4" style="color:#0e2b42;">Admin Login</h3>

        <!-- Error Message -->
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required autofocus>
            </div>
            <div class="mb-3 text-start">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-login w-100 mt-2">Login</button>
        </form>
    </div>
</body>
</html>
