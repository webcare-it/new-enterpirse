<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'id' => 48,
                'category_name' => 'Home Decor',
                'category_image' => 'https://backend.droploo.com/category/1750765246.webp',
                'slug' => 'home-decor',
                'title' => 'Home Decor',
                'sub_title' => 'Beautiful Home Decoration Products',
                'description' => 'Explore premium home decoration products.',
                'button_name' => 'Shop Now',
                'button_link' => '/category/home-decor',
            ],
            [
                'id' => 50,
                'category_name' => 'Electronics',
                'category_image' => 'https://backend.droploo.com/category/1751088909.jpg',
                'slug' => 'electronics',
                'title' => 'Electronics',
                'sub_title' => 'Latest Electronics Collection',
                'description' => 'Find trending electronic products.',
                'button_name' => 'Shop Now',
                'button_link' => '/category/electronics',
            ],
            [
                'id' => 51,
                'category_name' => 'Life Style',
                'category_image' => 'https://backend.droploo.com/category/1751089046.jpg',
                'slug' => 'life-style',
                'title' => 'Life Style',
                'sub_title' => 'Modern Lifestyle Products',
                'description' => 'Upgrade your lifestyle with quality products.',
                'button_name' => 'Shop Now',
                'button_link' => '/category/life-style',
            ],
            [
                'id' => 52,
                'category_name' => 'Smart Gadgets',
                'category_image' => 'https://backend.droploo.com/category/1751089073.jpg',
                'slug' => 'smart-gadgets',
                'title' => 'Smart Gadgets',
                'sub_title' => 'Smart Technology Collection',
                'description' => 'Discover innovative gadgets.',
                'button_name' => 'Shop Now',
                'button_link' => '/category/smart-gadgets',
            ],
            [
                'id' => 53,
                'category_name' => 'Metal Items',
                'category_image' => 'https://backend.droploo.com/category/1751089102.jpg',
                'slug' => 'metal-items',
                'title' => 'Metal Items',
                'sub_title' => 'Durable Metal Products',
                'description' => 'High-quality metal products for everyday use.',
                'button_name' => 'Shop Now',
                'button_link' => '/category/metal-items',
            ],
            [
                'id' => 54,
                'category_name' => 'All Foods',
                'category_image' => 'https://backend.droploo.com/category/1751089135.jpg',
                'slug' => 'all-foods',
                'title' => 'All Foods',
                'sub_title' => 'Food & Grocery Collection',
                'description' => 'Browse food and grocery products.',
                'button_name' => 'Shop Now',
                'button_link' => '/category/all-foods',
            ],
            [
                'id' => 55,
                'category_name' => 'Home & Kitchen',
                'category_image' => 'https://backend.droploo.com/category/1751089170.png',
                'slug' => 'home-kitchen',
                'title' => 'Home & Kitchen',
                'sub_title' => 'Kitchen Essentials',
                'description' => 'Everything you need for home and kitchen.',
                'button_name' => 'Shop Now',
                'button_link' => '/category/home-kitchen',
            ],
            [
                'id' => 56,
                'category_name' => "Men's Fashions",
                'category_image' => 'https://backend.droploo.com/category/1751293135.jpg',
                'slug' => 'mens-fashions',
                'title' => "Men's Fashions",
                'sub_title' => 'Fashion For Men',
                'description' => 'Latest men fashion products.',
                'button_name' => 'Shop Now',
                'button_link' => '/category/mens-fashions',
            ],
            [
                'id' => 57,
                'category_name' => "Woman's Fashion",
                'category_image' => 'https://backend.droploo.com/category/1751295131.png',
                'slug' => 'womans-fashion',
                'title' => "Woman's Fashion",
                'sub_title' => 'Fashion For Women',
                'description' => 'Stylish fashion collection for women.',
                'button_name' => 'Shop Now',
                'button_link' => '/category/womans-fashion',
            ],
            [
                'id' => 58,
                'category_name' => "Kid's Fashion",
                'category_image' => 'https://backend.droploo.com/category/1752747161.jpg',
                'slug' => 'kids-fashion',
                'title' => "Kid's Fashion",
                'sub_title' => 'Fashion For Kids',
                'description' => 'Trendy fashion products for kids.',
                'button_name' => 'Shop Now',
                'button_link' => '/category/kids-fashion',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
