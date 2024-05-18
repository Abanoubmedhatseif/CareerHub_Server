<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndustryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use Cloudinary\Asset\Analytics;

Route::get('params', [JobPostController::class, 'filterParams']);
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return response()->json($request->user());
    });
    Route::get('user/me', function (Request $request) {
        return response()->json($request->user());
    });
    Route::post('user/logout', [LogoutController::class, 'logout']);
    Route::put('user', [UserController::class, 'update']);
});

Route::middleware(['auth:sanctum', 'onlyAdmin'])->group(function () { // Important to have this before other job-posts route
    Route::get('analytics', [AnalyticsController::class, 'getAnalytics']);
    Route::get('job-posts/pending', [JobPostController::class, 'pendingPosts']);
    Route::get('job-posts/rejected', [JobPostController::class, 'rejectedPosts']);
    Route::put('job-posts/{id}/approve', [JobPostController::class, 'approve']);
    Route::put('job-posts/{id}/reject', [JobPostController::class, 'reject']);
    Route::put('job-posts/{id}/pend', [JobPostController::class, 'pend']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
});

Route::get('job-posts/search', [JobPostController::class, 'search']);
Route::get('job-posts', [JobPostController::class, 'index']);
Route::get('job-posts/{id}', [JobPostController::class, 'show']);


Route::middleware(['auth:sanctum', 'onlyEmployer'])->group(function () {
    Route::post('job-posts', [JobPostController::class, 'store']);
    Route::put('job-posts/{id}', [JobPostController::class, 'update'])->middleware(['onlyJobPostOwner']);
    Route::delete('job-posts/{id}', [JobPostController::class, 'destroy'])->middleware(['onlyJobPostOwner']);
    Route::get('job-posts/{id}/applications', [JobPostController::class, 'jobPostApplications'])->middleware(['onlyJobPostOwner']);
    Route::get('employer/job-posts', [JobPostController::class, 'employerJobPosts']);
});


Route::get('skills', [SkillController::class, 'index']);
Route::get('skills/{skill}', [SkillController::class, 'show']);
Route::middleware(['auth:sanctum', 'onlyAdmin'])->group(function () {
    Route::apiResource('skills', SkillController::class)->except(['index', 'show']);
});

Route::get('industries', [IndustryController::class, 'index']);
Route::get('industries/{id}', [IndustryController::class, 'show']);
Route::middleware(['auth:sanctum', 'onlyAdmin'])->group(function () {
    Route::apiResource('industries', IndustryController::class)->except(['index', 'show']);
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('applications', [ApplicationController::class, 'index'])->middleware(['onlyAdmin']);
    Route::get('employer/applications', [ApplicationController::class, 'applicationsSubmittedToEmployer'])->middleware(['onlyEmployer']);
    Route::get('candidate/applications', [ApplicationController::class, 'candidateApplications'])->middleware(['onlyCandidate']);
    Route::put('applications/{id}/approve', [ApplicationController::class, 'approve'])->middleware(['onlyApplicationEmployer']);
    Route::put('applications/{id}/reject', [ApplicationController::class, 'reject'])->middleware(['onlyApplicationEmployer']);
    Route::post('job-posts/{id}/applications', [ApplicationController::class, 'store'])->middleware(['onlyCandidate']);
    Route::get('applications/{id}', [ApplicationController::class, 'show'])->middleware(['applicationCandidateOrEmployerOrAdmin']);
    Route::delete('applications/{id}', [ApplicationController::class, 'destroy'])->middleware(['onlyApplicationCandidate']);
});
