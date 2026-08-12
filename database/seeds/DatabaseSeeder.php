<?php

use Database\Seeders\BlogSeeder;
use Database\Seeders\SectionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // UserSeeder::class,
            // BusinessSettingSeeder::class,
            // PageSeeder::class,
            // RoleSeeder::class,
            // SectionSeeder::class,
            // BlogSeeder::class,
        ]);
    }
}
