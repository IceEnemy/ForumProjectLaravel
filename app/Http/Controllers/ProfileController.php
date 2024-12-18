<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected $firebaseService;

    /**
     * Constructor to inject FirebaseService.
     */
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Show the profile page with the user's information.
     */
    public function showProfile()
    {
        $user = Auth::user(); // Get the authenticated user
        return view('profile.show', compact('user'));
    }

    /**
     * Handle profile picture upload and save to Firebase Storage.
     */
    public function uploadProfilePicture(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');

            // Define the path for the file in Firebase Storage
            $path = 'profile_pictures/' . $user->id . '/' . uniqid() . '.' . $file->getClientOriginalExtension();

            try {
                // Upload the file to Firebase Storage
                $url = $this->firebaseService->uploadFile($file, $path);

                // Update the user's profile picture URL in the database
                $user->profile_picture = $url;
                /** @var \App\Models\User $user **/
                $user->save();

                return redirect()->back()->with('success', 'Profile picture updated successfully.');
            } catch (\Exception $e) {
                // Handle any errors during upload
                return redirect()->back()->with('error', 'Failed to upload profile picture: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'No file was uploaded.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $user->update([
            'username' => $request->username,
        ]);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {   
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
        //     // Log::info('Password mismatch for user ID: ' . $user->id);
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
