<?php

namespace App\Http\Middleware;

use Closure;

class EventOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Grab user from token
        $user = $request->user();
        if ($user->is_admin) return $next($request);

        // Grab lab id
        $event_id = $request->route()->parameter('event')->id;

        // Check if user is an admin member of this lab
        $event = $user->events()->where('id', $event_id)->first();
        if ($event == null) {
            return response()->json(['message' => 'Access denied: user does not own event.'], 401);
        }

        return $next($request);
    }
}
