<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SectionConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = [
            ['key' => 'sliders',       'order' => 1, 'isActive' => true],
            ['key' => 'features',      'order' => 2, 'isActive' => true],
            ['key' => 'new_arrivals',  'order' => 3, 'isActive' => true],
            ['key' => 'todays_deal',   'order' => 4, 'isActive' => true],
            ['key' => 'best_selling',  'order' => 5, 'isActive' => true],
            ['key' => 'featured',      'order' => 6, 'isActive' => true],
            ['key' => 'categories',    'order' => 7, 'isActive' => true],
            ['key' => 'brands',        'order' => 8, 'isActive' => true],
            ['key' => 'campaigns',     'order' => 9, 'isActive' => true],
        ];

        DB::table('section_configs')->insert($sections);
    }
}
