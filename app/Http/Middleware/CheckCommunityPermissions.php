<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Community;

class CheckCommunityPermissions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Fetch the community from the route or abort if not found
        $community = $request->route('community');

        if (is_numeric($community)) {
            $community = Community::with('administrators')->findOrFail($community);
        }

        // Ensure the community is available
        if (!$community) {
            return abort(404, 'Community not found.');
        }

        // Check for settings permissions (owner or administrator)
        if ($request->routeIs('community.settings', 'community.update')) {
            if (
                auth()->id() !== $community->owner_id &&
                !$community->administrators->contains(auth()->id())
            ) {
                return redirect()->route('community.show', $community->id)
                    ->with('error', 'You do not have permission to access community settings.');
            }
        }

        // Check for admin management or delete permissions (owner only)
        if ($request->routeIs('community.addAdmin', 'community.removeAdmin', 'community.delete')) {
            if (auth()->id() !== $community->owner_id) {
                return redirect()->route('community.show', $community->id)
                    ->with('error', 'Only the owner can perform this action.');
            }
        }

        return $next($request);
    }
}
