<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Validator;

class AuthController extends Controller
{
    // Handle form submission
    public function store(Request $request)
    {
        // Validate user input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8', // Minimum length of 8 characters
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[0-9]/', // At least one number
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        // Save user to database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Employee',
            'phone' => $request->phone,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
        ]);

        // Redirect or show success
        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    // Authenticate user
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Clear any cached previous pages
            return redirect()->intended(route(strtolower($user->role).'.dashboard'))
                             ->withHeaders([
                                 'Cache-Control' => 'no-cache, no-store, must-revalidate',
                                 'Pragma' => 'no-cache',
                                 'Expires' => '0'
                             ]);
        }
    
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    // Log out the user
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login'); // Redirect to login after logout
    }

    public function login()
    {
        return view('TDEIS.public.login');
    }

    public function register()
    {
        return view('TDEIS.public.register');
    }

    public function forgot()
    {
        return view('TDEIS.public.forgot');
    }

    // Send Reset Password Link
    public function sendResetLink(Request $request)
    {
        // Validate email input
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Send reset link
        $response = Password::sendResetLink(
            $request->only('email')
        );

        // Check if the reset link was sent successfully
        if ($response == Password::RESET_LINK_SENT) {
            return back()->with('status', 'We have emailed you the password reset link!');
        } else {
            return back()->withErrors(['email' => 'We were unable to send the reset link.']);
        }
    }

    // Handle password reset
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if the token exists in the password_resets table
        $passwordReset = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return redirect()->back()->withErrors(['token' => 'Invalid or expired token.']);
        }

        // Update the user's password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token from the password_resets table
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Your password has been reset successfully. You can now login.');
    }
}
