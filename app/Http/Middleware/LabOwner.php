<?php

namespace App\Http\Middleware;

use Closure;

class LabOwner
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
        $lab_id = $request->route()->parameter('lab')->id;

        // Check if user is an admin member of this lab
        $lab = $user->labs()->where('id', $lab_id)->first();
        if ($lab == null) {
            return response()->json(['message' => 'Access denied: user is not a member of this lab.'], 401);
        }
        if ($lab->pivot->role != 1 && $lab->pivot->role != 2) {
            return response()->json(['message' => 'Access denied: user is not an admin of this lab.'], 401);
        }

        return $next($request);
    }
}
