<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use \App\Models\Industry;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Industry::factory(15)->create();
    }
}
