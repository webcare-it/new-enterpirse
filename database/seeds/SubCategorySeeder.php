<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\SubCategory;

class SubCategorySeeder extends Seeder
{
    public function run()
    {
        $subCategories = [

            [
                'id' => 62,
                'category_id' => 57,
                'name' => 'Woman Hijab',
                'slug' => 'woman-hijab',
                'image' => null,
            ],
            [
                'id' => 64,
                'category_id' => 57,
                'name' => 'Woman Khimar',
                'slug' => 'woman-khimar',
                'image' => null,
            ],
            [
                'id' => 65,
                'category_id' => 57,
                'name' => 'Woman Jilbab',
                'slug' => 'woman-jilbab',
                'image' => null,
            ],
            [
                'id' => 73,
                'category_id' => 56,
                'name' => 'Salat Hijab',
                'slug' => 'salat-hijab',
                'image' => null,
            ],

            [
                'id' => 66,
                'category_id' => 56,
                'name' => "Men's Panjabi",
                'slug' => 'mens-panjabi',
                'image' => null,
            ],
            [
                'id' => 67,
                'category_id' => 56,
                'name' => "Men's Jubba",
                'slug' => 'mens-jubba',
                'image' => null,
            ],
            [
                'id' => 68,
                'category_id' => 58,
                'name' => "Men's Kabli",
                'slug' => 'mens-kabli',
                'image' => null,
            ],

            [
                'id' => 69,
                'category_id' => 58,
                'name' => 'Kids Jubba',
                'slug' => 'kids-jubba',
                'image' => null,
            ],
            [
                'id' => 70,
                'category_id' => 58,
                'name' => 'Kids Panjabi',
                'slug' => 'kids-panjabi',
                'image' => null,
            ],
            [
                'id' => 71,
                'category_id' => 58,
                'name' => 'Kids Hijab',
                'slug' => 'kids-hijab',
                'image' => null,
            ],
            [
                'id' => 72,
                'category_id' => 57,
                'name' => 'Kids Khimar',
                'slug' => 'kids-khimar',
                'image' => null,
            ],
            [
                'id' => 74,
                'category_id' => 58,
                'name' => 'Kids Borka',
                'slug' => 'kids-borka',
                'image' => null,
            ],

        ];

        foreach ($subCategories as $subCategory) {
            SubCategory::updateOrCreate(
                ['id' => $subCategory['id']],
                $subCategory
            );
        }
    }
}
