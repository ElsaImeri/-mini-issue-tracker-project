<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Issue Tracker') }} - Login</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        .login-container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .login-header {
            width: 100%;
            height: 200px;
            background: linear-gradient(to right, #4a6cf7, #2e4fd0);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
        }
        .login-header-content {
            text-align: center;
            z-index: 2;
            padding: 20px;
        }
        .login-header-content i {
            font-size: 64px;
            margin-bottom: 15px;
        }
        .login-header-content h2 {
            font-weight: 600;
            font-size: 28px;
            margin: 0;
        }
        .login-form {
            padding: 30px;
        }
        .form-control {
            padding-left: 45px;
            height: 50px;
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #4a6cf7;
            box-shadow: 0 0 0 0.25rem rgba(74, 108, 247, 0.15);
        }
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 5;
            font-size: 18px;
        }
        .btn-login {
            background: #4a6cf7;
            border: none;
            color: white;
            padding: 12px 20px;
            width: 100%;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
            height: 50px;
            font-size: 16px;
        }
        .btn-login:hover {
            background: #3a56d4;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(74, 108, 247, 0.3);
        }
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.5rem 0;
        }
        .form-check-input:checked {
            background-color: #4a6cf7;
            border-color: #4a6cf7;
        }
        .forgot-link {
            color: #4a6cf7;
            text-decoration: none;
            transition: color 0.3s;
            font-size: 14px;
        }
        .forgot-link:hover {
            color: #2e4fd0;
            text-decoration: underline;
        }
        .login-footer {
            text-align: center;
            margin-top: 2rem;
            color: #6c757d;
            font-size: 14px;
        }
        .system-name {
            font-weight: 700;
            color: #4a6cf7;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }
        .input-error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .auth-session-status {
            margin-bottom: 1rem;
            padding: 0.75rem 1.25rem;
            border-radius: 0.375rem;
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <div class="login-header-content">
                <i class="bi bi-bug-fill"></i>
                <h2>Issue Tracker System</h2>
            </div>
        </div>

        <!-- Login Form -->
        <div class="login-form">
            <!-- Session Status -->
            @if (session('status'))
                <div class="auth-session-status mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <i class="bi bi-envelope input-icon"></i>
                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email">
                    </div>
                    @error('email')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <i class="bi bi-lock input-icon"></i>
                        <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
                    </div>
                    @error('password')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me + Forgot Password -->
                <div class="form-options">
                    <div class="form-check">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label class="form-check-label" for="remember_me">Remember me</label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-login">Log in</button>
                </div>
            </form>

            <div class="login-footer">
                <p>© {{ date('Y') }} <span class="system-name">Issue Tracker</span>. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
