<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Application;

class OnlyApplicationCandidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $application = Application::find($request->route('id'));

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        if (!$request->user()->appliedJobs->contains($application->jobPost->id)) {
            return response()->json(['message' => 'Only the Candidate that owns this application can see it'], 422);
        }

        return $next($request);
    }
}
