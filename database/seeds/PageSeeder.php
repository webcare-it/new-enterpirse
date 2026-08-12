<?php

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default pages for boilerplate
        $pages = [
            [
                'type' => 'privacy_policy',
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<p>Add your privacy policy content here.</p>',
                'meta_title' => 'Privacy Policy',
                'meta_description' => 'Privacy Policy page',
                'keywords' => 'privacy, policy',
            ],
            [
                'type' => 'terms_and_conditions',
                'title' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'content' => '<p>Add your terms and conditions content here.</p>',
                'meta_title' => 'Terms & Conditions',
                'meta_description' => 'Terms and Conditions page',
                'keywords' => 'terms, conditions',
            ],
            [
                'type' => 'about_us',
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<p>Add your about us content here.</p>',
                'meta_title' => 'About Us',
                'meta_description' => 'About Us page',
                'keywords' => 'about, us',
            ],
            [
                'type' => 'contact_us',
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'content' => '<p>Add your contact us content here.</p>',
                'meta_title' => 'Contact Us',
                'meta_description' => 'Contact Us page',
                'keywords' => 'contact, us',
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
