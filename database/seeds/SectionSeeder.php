<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = [
            'Slider',
            'Counter',
            'Discover',
            'Videos',
            'Reviews',
            'Faqs',
            'Dropshiping Categories',
        ];

        foreach ($sections as $index => $section) {
            DB::table('sections')->insert([
                'section_name' => $section,
                'status'       => 'active',
                'position'     => $index + 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}
