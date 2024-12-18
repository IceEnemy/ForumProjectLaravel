<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Community;
use App\Models\Post;
use App\Services\FirebaseService;
use App\Models\Comment;

class PostController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function show(Post $post)
    {
        $communityPosts = $post->community->posts()->where('id', '!=', $post->id)->get();
        $comments = $post->comments()->whereNull('parent_id')->with('replies')->get();

        return view('post.show', [
            'post' => $post,
            'communityPosts' => $communityPosts,
            'comments' => $comments,
        ]);
    }

    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Comment added successfully!');
    }


    public function store(Request $request, Community $community)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageUrl = null;

        // Upload the image to Firebase if provided
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imagePath = 'post_images/' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $imageUrl = $this->firebaseService->uploadFile($imageFile, $imagePath);
        }

        // Create the post
        $community->posts()->create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imageUrl,
        ]);

        return redirect()->route('community.show', $community->id)->with('success', 'Post added!');
    }

    public function toggleUpvote(Post $post)
    {
        $userId = auth()->id();

        // Remove downvote if it exists
        $post->downvotes()->detach($userId);

        // Toggle upvote
        if ($post->upvotes()->where('user_id', $userId)->exists()) {
            $post->upvotes()->detach($userId); // Remove upvote
        } else {
            $post->upvotes()->attach($userId); // Add upvote
        }

        return back();
    }

    public function toggleDownvote(Post $post)
    {
        $userId = auth()->id();

        // Remove upvote if it exists
        $post->upvotes()->detach($userId);

        // Toggle downvote
        if ($post->downvotes()->where('user_id', $userId)->exists()) {
            $post->downvotes()->detach($userId); // Remove downvote
        } else {
            $post->downvotes()->attach($userId); // Add downvote
        }

        return

            back();
    }

    public function edit(Post $post)
    {
        return view('post.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Upload the new image to Firebase
            $imageFile = $request->file('image');
            $imagePath = 'post_images/' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $newImageUrl = $this->firebaseService->uploadFile($imageFile, $imagePath);

            // Delete the old image from Firebase if it exists
            if ($post->image) {
                $this->firebaseService->deleteFile($post->image);
            }

            // Update the post's image URL
            $post->image = $newImageUrl;
        }

        // Update the content and timestamp
        $post->content = $request->content;
        $post->updated_at = now();
        $post->save();

        return redirect()->route('post.show', $post->id)
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        // Delete the image from Firebase if it exists
        if ($post->image) {
            $this->firebaseService->deleteFile($post->image);
        }

        // Delete the post
        $post->delete();

        return redirect()->route('community.show', $post->community_id)
            ->with('success', 'Post deleted successfully.');
    }
}
