<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login requests.
     */
    public function login(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // If email does not exist
            return back()->withErrors(['email' => 'The email address is not registered.']);
        }

        // Check if the password is correct
        if (!Hash::check($request->password, $user->password)) {
            // If the password is incorrect
            return back()->withErrors(['password' => 'The password you entered is incorrect.']);
        }

        // Additional checks, e.g., account status
        if ($user->status === 'locked') {
            // If the account is locked
            return back()->withErrors(['email' => 'This account is locked. Please contact support.']);
        }

        // Attempt to log in the user
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            // Redirect to dashboard or intended route
            return redirect()->intended('dashboard')->with('success', 'Logged in successfully!');
        }

        // If login fails for any other reason
        return back()->withErrors(['login' => 'Failed to log in. Please try again.']);
    }

    /**
     * Handle logout requests.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Logged out successfully!');
    }
}
