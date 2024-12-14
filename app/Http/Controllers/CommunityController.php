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
        ]);

        $imageUrl = null;
        if ($request->hasFile('header_image')) {
            $file = $request->file('header_image');
            $path = 'community_images/' . uniqid() . '.' . $file->getClientOriginalExtension();
            $imageUrl = $firebaseService->uploadFile($file, $path);
        }

        // Create the community
        Community::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'header_image' => $imageUrl,
            'owner_id' => auth()->id(), // Store the ID of the authenticated user as the community owner
        ]);

        // Redirect to the home page with a success message
        return redirect()->route('home')->with('success', 'Community created successfully!');
    }

}
