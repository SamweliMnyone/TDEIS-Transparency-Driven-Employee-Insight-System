@extends('TDEIS/public/layout/app')

@section('content')

<title>TDEIS | Register</title>
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
.card-hover:hover {
    transform: scale(1.02);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    transition: 0.3s ease-in-out;
}
input.form-control, select.form-control {
    border-radius: 10px;
    border: 1px solid #ccc;
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
    transition: background 0.4s ease;
}
.btn-custom:hover {
    background: linear-gradient(90deg, #0072ff, #00c6ff);
}
.text-danger {
    font-size: 14px;
}
</style>

<div class="sufee-login d-flex align-content-center flex-wrap" style="min-height: 100vh; background: #f4f7fa;">
    <div class="container">
        <div class="login-content">
            <div class="login-logo" style="
                text-align: center;
                font-size: 24px;
                font-weight: 800;
                margin-bottom: 30px;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(90deg, #00c6ff, #0072ff);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                animation: fadeIn 2s ease-in-out;
            ">
                TRANSPARENCE DRIVEN EMPLOYEE INSIGHT SYSTEM
            </div>

            <div class="login-form card card-hover" style="
                max-width: 600px;
                margin: 0 auto;
                background: white;
                border-radius: 20px;
                padding: 40px;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            ">
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <h3 style="text-align: center; font-size: 24px; font-weight: 700;
                        background: linear-gradient(90deg, #00c6ff, #0072ff);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;">Create Your Account</h3>

                    <div class="form-group mt-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Your full name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mt-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Your email address">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mt-3">
                        <label>Password</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Create password">
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>



                    <div class="form-group mt-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}" placeholder="Phone number">
                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mt-3">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address') }}" placeholder="Address">
                        @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mt-3">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth"
                            class="form-control @error('date_of_birth') is-invalid @enderror"
                            value="{{ old('date_of_birth') }}">
                        @error('date_of_birth') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mt-3">
                        <label>Gender</label>
                        <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                            <option value="">-- Select Gender --</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-custom btn-block mt-4">Register</button>

                    <div class="register-link mt-4 text-center">
                        <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
