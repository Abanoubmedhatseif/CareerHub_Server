<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobPost;
use App\Models\User;

class JobPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employers = User::where('role', 'employer')->get();

        $employers->each(function ($employer) {
            $employer->postedJobs()->saveMany(JobPost::factory()->count(rand(0, 10))->make());
        });
    }
}
