<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\JobPost;

class OnlyJobPostOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $jobPostId = $request->route('id');
        $jobPost = JobPost::find($jobPostId);

        if (!$jobPost) {
            return response()->json(['message' => 'Job post not found'], 404);
        }

        if ($jobPost->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Only Job Post Owner is allowed to preform this operation'], 403);
        }
        return $next($request);
    }
}
