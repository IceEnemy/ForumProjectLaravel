<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;
use App\Models\User;
use App\Services\FirebaseService;

class CommunityController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $communities = Community::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        })->get();

        if ($communities->isEmpty()) {
            $message = 'No communities found for your search.';
        } else {
            $message = null;
        }

        return view('home', compact('communities', 'message'));
    }

    public function store(Request $request, FirebaseService $firebaseService)
    {
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

        $community = Community::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'header_image' => $headerImageUrl,
            'rules' => $request->input('rules', null),
            'profile_image' => $profileImageUrl,
            'owner_id' => auth()->id(),
        ]);

        $community->members()->attach(auth()->id());

        return redirect()->route('home')->with('success', 'Community created successfully!');
    }
    // public function show($id)
    // {
    //     $community = Community::findOrFail($id);

    //     return view('community.show', compact('community'));
    // }
    public function show(Request $request, $id)
    {
        // Find the community with its related posts
        $community = Community::findOrFail($id);

        // Get the search term and sort option from the request
        $search = $request->input('search', '');
        $sortBy = $request->input('sort', 'latest');

        // Filter and sort posts based on the search term and sort option
        $communityPosts = $community->posts()
            ->withCount(['upvotes', 'downvotes']) // Include upvotes and downvotes count
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            })
            ->when($sortBy, function ($query, $sortBy) {
                if ($sortBy === 'latest') {
                    return $query->orderBy('created_at', 'desc');
                } elseif ($sortBy === 'oldest') {
                    return $query->orderBy('created_at', 'asc');
                } elseif ($sortBy === 'most_upvoted') {
                    return $query->orderByRaw('(upvotes_count - downvotes_count) DESC');
                }
            })
            ->get();

        // Pass the filtered and sorted posts, search term, and sort option to the view
        return view('community.show', compact('community', 'communityPosts', 'search', 'sortBy'));
    }

    public function join($id)
    {
        $community = Community::findOrFail($id);

        if (!$community->members->contains(auth()->id())) {
            $community->members()->attach(auth()->id());
        }

        return redirect()->route('community.show', $id)->with('success', 'Joined community successfully!');
    }
    // public function leave($id)
    // {
    //     $community = Community::findOrFail($id);

    //     if ($community->members->contains(auth()->id())) {
    //         $community->members()->detach(auth()->id());
    //     }

    //     return redirect()->route('community.show', $id)->with('success', 'Left community successfully!');
    // }
    public function leave($id)
    {
        $community = Community::findOrFail($id);
        $user = auth()->user();

        // Prevent the owner from leaving the community
        if ($community->owner_id === $user->id) {
            return redirect()->route('community.show', $id)->with('error', 'Owners cannot leave their own community.');
        }

        // Check if the user is part of the community
        if ($community->members->contains($user)) {
            // Remove the user from the community
            $community->members()->detach($user->id);

            // Remove the user from the admin role if they are an admin
            if ($community->administrators->contains($user)) {
                $community->administrators()->detach($user->id);
            }
        }

        return redirect()->route('community.show', $id)->with('success', 'You have left the community.');
    }

    public function settings(Request $request, $communityId)
    {
        $community = Community::with(['members', 'administrators'])->findOrFail($communityId);

        // Search for administrators
        $searchAdmins = $request->input('search_admins');
        $filteredAdmins = $community->administrators->filter(function ($admin) use ($searchAdmins) {
            return empty($searchAdmins) || stripos($admin->username, $searchAdmins) !== false;
        });

        // Search for members
        $searchMembers = $request->input('search_members');
        $filteredMembers = $community->members
            ->diff($community->administrators)
            ->filter(function ($member) use ($searchMembers) {
                return empty($searchMembers) || stripos($member->username, $searchMembers) !== false;
            });

        return view('community.settings', compact('community', 'filteredAdmins', 'filteredMembers'));
    }

    public function update(Request $request, Community $community)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'rules' => 'nullable|string',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('header_image')) {
            $imageFile = $request->file('header_image');
            $imagePath = 'community_headers/' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $imageUrl = $this->firebaseService->uploadFile($imageFile, $imagePath);

            if ($community->header_image) {
                $this->firebaseService->deleteFile($community->header_image);
            }

            $community->header_image = $imageUrl;
        }

        if ($request->hasFile('profile_image')) {
            $imageFile = $request->file('profile_image');
            $imagePath = 'community_profiles/' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $imageUrl = $this->firebaseService->uploadFile($imageFile, $imagePath);

            if ($community->profile_image) {
                $this->firebaseService->deleteFile($community->profile_image);
            }

            $community->profile_image = $imageUrl;
        }

        $community->update($request->only(['name', 'description', 'rules']));

        return redirect()->route('community.settings', $community->id)->with('success', 'Community updated successfully.');
    }

    public function addAdmin(Community $community, User $user)
    {
        $community->administrators()->attach($user->id);

        return redirect()->route('community.settings', $community->id)->with('success', 'Admin added successfully.');
    }

    public function removeAdmin(Community $community, User $user)
    {
        $community->administrators()->detach($user->id);

        return redirect()->route('community.settings', $community->id)->with('success', 'Admin removed successfully.');
    }

    public function destroy(Community $community)
    {
        if ($community->header_image) {
            $this->firebaseService->deleteFile($community->header_image);
        }
        if ($community->profile_image) {
            $this->firebaseService->deleteFile($community->profile_image);
        }

        $community->delete();

        return redirect()->route('home')->with('success', 'Community deleted successfully.');
    }
}
