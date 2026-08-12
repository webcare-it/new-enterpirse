<?php

namespace Database\Seeders;

use App\Models\Admin\Blog;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 50; $i++) {
            $title = $faker->sentence(6);
            Blog::create([
                'thumbnail'         => rand(1, 20),
                'main_image'        => rand(1, 20),
                'blog_title'        => $title,
                'slug'              => Str::slug($title . '-' . $i),
                'tags'              => implode(',', $faker->words(5)),
                'short_description' => $faker->paragraph(),
                'long_description'  => $faker->realText(2000),
                'user_id'           => 1,
                'meta_title'        => $title,
                'meta_image'        => rand(1, 20),
                'meta_description'  => $faker->paragraph(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}
