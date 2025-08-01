<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            ['name' => 'IT Department'],
            ['name' => 'Front-End Developer'],
            ['name' => 'Back-End Developer'],
            ['name' => 'Full-Stack Developer'],
            ['name' => 'UI/UX Designer'],
            ['name' => 'QA Engineer'],
            ['name' => 'Mobile Developer (Android)'],
            ['name' => 'Mobile Developer (iOS)'],
        ];

        foreach ($divisions as $division) {
            Division::updateOrCreate(
                ['name' => $division['name']], 
                $division
            );
        }
    }
}
