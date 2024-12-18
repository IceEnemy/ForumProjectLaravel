<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;
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
    public function show($id)
    {
        $community = Community::findOrFail($id);

        return view('community.show', compact('community'));
    }
    public function join($id)
    {
        $community = Community::findOrFail($id);

        if (!$community->members->contains(auth()->id())) {
            $community->members()->attach(auth()->id());
        }

        return redirect()->route('community.show', $id)->with('success', 'Joined community successfully!');
    }
    public function leave($id)
    {
        $community = Community::findOrFail($id);

        if ($community->members->contains(auth()->id())) {
            $community->members()->detach(auth()->id());
        }

        return redirect()->route('community.show', $id)->with('success', 'Left community successfully!');
    }
}
