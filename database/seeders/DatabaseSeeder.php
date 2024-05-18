<?php

namespace Database\Seeders;

use Database\Seeders\ApplicationSeeder;
use Database\Seeders\JobPostSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\JobPostSkillSeeder;
use Database\Seeders\JobPostIndustrySeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            JobPostSeeder::class,
            ApplicationSeeder::class,
            IndustrySeeder::class,
            JobPostIndustrySeeder::class,
            SkillSeeder::class,
            JobPostSkillSeeder::class,
        ]);
    }
}
