<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Application;
use Illuminate\Support\Facades\DB;
use App\Models\JobPost;
use App\Models\User;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Application::factory()->count(10)->create();

        DB::table('applications')->delete();

        $jobPosts = JobPost::all();
        $users = User::all()->filter(function ($user) {
            return $user->isCandidate();
        });


        foreach ($users as $user) {
            $attachedJobPosts = $jobPosts->random(rand(10, 10));

            $user->appliedJobs()->attach($attachedJobPosts, [
                'resume_path' => '/storage/candidate-resumes/' . fake()->word . '.pdf',
            ]);
        }
    }
}
