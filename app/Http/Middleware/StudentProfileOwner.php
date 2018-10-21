<?php

namespace App\Http\Middleware;

use Closure;

class StudentProfileOwner
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
        // Grab student id
        $student_id = $request->route()->parameter('student')->id;

        // Check if user owns this student profile
        $student = $user->student;
        if ($student == null) {
            return response()->json(['message' => 'Access denied: user is not a student.'], 401);
        }
        if ($student->id != $student_id) {
            return response()->json(['message' => 'Access denied: user does not own this student profile.'], 401);
        }

        return $next($request);
    }
}
