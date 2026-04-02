<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if(!$user) return $next($request);

        $isComplete = !empty($user->blood_group) && !empty($user->area_id);

        if(!$isComplete && !$request->routeIs('profile.complete', 'profile.complete.store', 'locations.*')) {
            return redirect()->route('profile.complete');
        }

        return $next($request);
    }
}
