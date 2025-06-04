<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHomestaurantApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

    if (
        $user &&
        $user->user_type == 2 &&
        (! $user->vendorApplication || $user->vendorApplication->is_approved != 1)
    ) {
        abort(403, 'Your homestaurant is not approved yet.');
    }
        return $next($request);
    }
}
