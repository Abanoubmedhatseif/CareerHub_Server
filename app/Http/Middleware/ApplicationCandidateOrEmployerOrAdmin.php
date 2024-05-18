<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Application;

class ApplicationCandidateOrEmployerOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $application = Application::with('jobPost')->find($request->route('id'));

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        if ($user->isAdmin()) {
            return $next($request);
        }

        if ($application->user_id === $user->id) {
            return $next($request);
        }

        if ($application->jobPost && $application->jobPost->user_id === $user->id) {
            return $next($request);
        }

        return response()->json(['message' => 'Only Application applicant or employer submitted the application to or admin can preform this action'], 403);
    }
}
