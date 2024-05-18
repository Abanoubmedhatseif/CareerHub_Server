<?php

namespace Database\Seeders;

use App\Models\JobPost;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobPostSkillSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('job_post_skill')->delete();

        $jobPosts = JobPost::all();
        $skills = Skill::all();


        foreach ($jobPosts as $post) {
            $attachedSkills = $skills->random(rand(2, 3));
            $post->skills()->sync($attachedSkills);
        }
    }
}
