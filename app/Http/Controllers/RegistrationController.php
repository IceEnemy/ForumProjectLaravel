<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        // Validate the input
        $request->validate([
            'username' => [
                'required',
                'unique:users,username',
                'min:4',
                'max:20',
                'regex:/^[a-zA-Z0-9_]+$/', // Only letters, numbers, and underscores
            ],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ], [
            'username.required' => 'Username is required.',
            'username.unique' => 'The username is already taken.',
            'username.min' => 'Username must be at least 4 characters.',
            'username.max' => 'Username must not exceed 20 characters.',
            'username.regex' => 'Username can only contain letters, numbers, and underscores.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'The email address is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        // Create the user
        $user = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Redirect to the login page with a success message
        return redirect()->route('login')->with('status', 'Registration successful! Please login.');
    }
}
