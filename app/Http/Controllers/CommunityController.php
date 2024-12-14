<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;
use App\Services\FirebaseService;

class CommunityController extends Controller
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
     * Display a listing of the communities.
     */
    public function index(Request $request)
    {
        // Get the search term from the request
        $search = $request->input('search', '');

        // Query the communities, filtering by name or description if search term is provided
        $communities = Community::when($search, function($query, $search) {
            return $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%');
        })->get();

        // Check if no communities are found
        if ($communities->isEmpty()) {
            $message = 'No communities found for your search.';
        } else {
            $message = null;
        }

        // Return the 'home' view instead of 'communities.index' and pass the message if no communities found
        return view('home', compact('communities', 'message'));
    }

    /**
     * Show the form for creating a new community.
     */
    public function create()
    {
        return view('community.create');
    }

    /**
     * Store a newly created community in storage.
     */
    public function store(Request $request, FirebaseService $firebaseService)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rules' => 'nullable|string|max:1000',
        ]);

        $headerImageUrl = null;
        $profileImageUrl = null;

        if ($request->hasFile('header_image')) {
            $headerFile = $request->file('header_image');
            $headerPath = 'community_headers/' . uniqid() . '.' . $headerFile->getClientOriginalExtension();
            $headerImageUrl = $firebaseService->uploadFile($headerFile, $headerPath);
        }
        if ($request->hasFile('profile_image')) {
            $profileFile = $request->file('profile_image');
            $profilePath = 'community_profiles/' . uniqid() . '.' . $profileFile->getClientOriginalExtension();
            $profileImageUrl = $firebaseService->uploadFile($profileFile, $profilePath);
        }

        // Create the community
        Community::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'header_image' => $headerImageUrl,
            'rules' => $request->input('rules', null),
            'profile_image' => $profileImageUrl,
            'owner_id' => auth()->id(), // Store the ID of the authenticated user as the community owner
        ]);

        // Redirect to the home page with a success message
        return redirect()->route('home')->with('success', 'Community created successfully!');
    }

}
