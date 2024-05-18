<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Application;

class OnlyApplicationEmployer
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

        if ($application->jobPost->employer->id !== $request->user()->id) {
            return response()->json(['message' => 'Only Employer to whom application submitted is allowed to preform this operation'], 403);
        }
        return $next($request);
    }
}
