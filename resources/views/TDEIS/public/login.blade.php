@extends('TDEIS/public/layout/app')

@section('content')
    <title>TDEIS | Login</title>
    
    <style>
        /* Animation for heading */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Smooth zoom on form card */
        .card-hover:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: 0.3s ease-in-out;
        }

        /* Custom inputs */
        input.form-control {
            border-radius: 10px;
            border: 1px solid #ccc;
            padding: 12px 15px;
            font-size: 16px;
        }

        /* Submit button style */
        .btn-custom {
            background: linear-gradient(90deg, #00c6ff, #0072ff);
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            transition: background 0.4s ease;
        }

        .btn-custom:hover {
            background: linear-gradient(90deg, #0072ff, #00c6ff);
        }
    </style>

    <div class="sufee-login d-flex align-content-center flex-wrap" style="min-height: 100vh; background: #f4f7fa;">
        <div class="container">
            <div class="login-content">
                <!-- Logo title with gradient text -->
                <div class="login-logo"
                    style="text-align: center; font-size: 24px; font-weight: 800; margin-bottom: 30px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(90deg, #00c6ff, #0072ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; animation: fadeIn 2s ease-in-out;">
                    TRANSPARENCE DRIVEN EMPLOYEE ENSIGHT SYSTEM
                </div>

                <!-- Notification Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Login card -->
                <div class="login-form card card-hover"
                    style="max-width: 480px; margin: 0 auto; background: white; border-radius: 20px; padding: 40px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15); transition: 0.3s ease;">
                    <!-- Login Form -->
                    <form action="{{ route('login.authenticate') }}" method="POST">
                        @csrf
                        <h3
                            style="font-size: 24px; font-weight: 700; background: linear-gradient(90deg, #00c6ff, #0072ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin: 10px auto; text-align: center; display: block;">
                            Login
                        </h3>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="Enter your email" value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter your password">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Forgot Password -->
                        <div class="checkbox text-right mb-3">
                            <a href="{{ route('forgot') }}">Forgot Password?</a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-custom btn-block btn-flat">
                            Login
                        </button>

                        <!-- Registration Link -->
                        <div class="register-link mt-4 text-center">
                            <p>Don't have an account? <a href="{{ route('register') }}">Register Here</a></p>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection