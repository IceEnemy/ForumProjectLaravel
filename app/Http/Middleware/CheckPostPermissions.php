<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Post;

class CheckPostPermissions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $post = $request->route('post'); // Use route model binding

        if (!$post) {
            return abort(404, 'Post not found.');
        }

        if ($request->routeIs('post.edit', 'post.update') && auth()->id() !== $post->user_id) {
            return abort(403, 'Only the author can edit this post.');
        }

        if (
            $request->routeIs('post.delete') &&
            auth()->id() !== $post->user_id &&
            auth()->id() !== $post->community->owner_id &&
            !$post->community->administrators->contains(auth()->user())
        ) {
            return abort(403, 'You are not authorized to delete this post.');
        }

        return $next($request);
    }

}
