@extends('TDEIS/public/layout/app')

@section('content')
<title>TDEIS | Forgot Password</title>

<style>
    .centered-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f4f7fa;
        padding: 30px;
    }

    .login-form {
        background: #fff;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    .login-form h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 25px;
        text-align: center;
        color: #333;
    }

    input.form-control {
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 16px;
    }

    .btn-custom {
        background: linear-gradient(90deg, #00c6ff, #0072ff);
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 10px;
        padding: 12px;
        width: 100%;
    }

    .btn-custom:hover {
        background: linear-gradient(90deg, #0072ff, #00c6ff);
    }
</style>

<div class="centered-wrapper">
    <div class="login-content text-center">

        <div class="login-form">
            <h3 style="
                font-size: 24px;
                font-weight: 700;
                background: linear-gradient(90deg, #00c6ff, #0072ff);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                margin: 10px auto;
                text-align: center;
                display: block;
            ">
                Forgot Password
            </h3>
            
            <!-- Success Message -->
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <!-- Form to Submit Email -->
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="form-group mb-4">
                    <label>Email address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>

                <button type="submit" class="btn btn-custom">Submit</button>
            </form>
        </div>
    </div>
</div>

@endsection
