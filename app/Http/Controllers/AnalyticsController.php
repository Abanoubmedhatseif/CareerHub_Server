<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\JobPost;
use App\Models\User;

class AnalyticsController extends Controller
{
    public function getAnalytics(Request $request)
    {
        $totalJobPosts = JobPost::count();
        $pendingJobPosts = JobPost::where('status', 'pending')->count();
        $rejectedJobPosts = JobPost::where('status', 'rejected')->count();
        $approvedJobPosts = JobPost::where('status', 'approved')->count();

        $totalUsers = User::count();
        $employersCount = User::where('role', 'employer')->count();
        $candidatesCount = User::where('role', 'candidate')->count();
        $adminsCount = User::where('role', 'admin')->count();

        $totalApplications = Application::count();

        // Return the analytics data as a JSON response
        return response()->json([
            'total_job_posts_count' => $totalJobPosts,
            'pending_job_posts_count' => $pendingJobPosts,
            'rejected_job_posts_count' => $rejectedJobPosts,
            'approved_job_posts_count' => $approvedJobPosts,
            'total_users_count' => $totalUsers,
            'employers_count' => $employersCount,
            'candidates_count' => $candidatesCount,
            'admins_count' => $adminsCount,
            'total_applications_count' => $totalApplications,
        ]);
    }
}
