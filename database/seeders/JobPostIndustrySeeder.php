<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Industry;
use App\Models\JobPost;

class JobPostIndustrySeeder extends Seeder
{
    public function run()
    {
        $jobPosts = JobPost::all();
        $industries = Industry::all();

        foreach ($jobPosts as $jobPost) {
            $jobPost->industries()->attach(
                $industries->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
