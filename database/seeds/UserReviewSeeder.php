<?php

namespace Database\Seeders;

use App\Models\Admin\Product;
use App\Models\Admin\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Rahim Uddin',
            'Karim Ahmed',
            'Sabbir Hossain',
            'Nasir Khan',
            'Ayesha Siddika',
            'Fatema Begum',
            'Nusrat Jahan',
            'Tasnim Akter',
            'Tanvir Rahman',
            'Imran Hossain',
            'Shakib Al Hasan',
            'Mushfiqur Rahim',
            'Sadia Islam',
            'Mim Akter',
            'Rumana Begum',
            'Jahangir Alam',
            'Mizanur Rahman',
            'Sajeeb Wazed',
            'Ariful Islam',
            'Hasibul Hasan',
        ];

        $cities = ['Dhaka', 'Chattogram', 'Sylhet', 'Khulna', 'Rajshahi', 'Barishal', 'Rangpur', 'Mymensingh'];

        $this->command->info('Creating 20 users…');

        foreach ($names as $i => $name) {
            $n     = $i + 1;
            $email = "user{$n}@example.com";

            if (User::where('email', $email)->exists()) {
                continue;
            }

            User::create([
                'name'              => $name,
                'email'             => $email,
                'phone'             => '017' . str_pad($n, 8, '0', STR_PAD_LEFT),
                'address'           => $cities[array_rand($cities)] . ', Bangladesh',
                'password'          => Hash::make('password123'),
                'email_verified_at' => now(),
            ]);
        }

        $userIds = User::where('user_type', 'customer')->pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->warn('⚠️  No customer users found. Seed users first.');
            return;
        }

        $this->command->info('  ' . count($userIds) . ' users available.');

        $commentsByRating = [
            5 => [
                'Excellent product, highly recommended!',
                'Very good quality. Worth the price.',
                'Exactly as described. Happy with the purchase.',
                'My family loved it. Will order again.',
                'Awesome! Exceeded my expectations.',
                'Perfect fit and finish. Very satisfied.',
                'Better than I expected. Highly recommended.',
                'Amazing quality for this price range.',
                'Great product. Fast shipping.',
                'Really liked the quality.',
                'Very useful, exactly what I needed.',
                'Will definitely buy again from this shop.',
                'অসাধারণ প্রোডাক্ট! সবাইকে রিকমেন্ড করবো।',
                'দাম অনুযায়ী কোয়ালিটি অনেক ভালো।',
                'একদম ছবির মতোই পেয়েছি। ধন্যবাদ।',
                'খুব দ্রুত ডেলিভারি পেয়েছি, প্রোডাক্টও ভালো।',
                'আমার পরিবারের সবাই পছন্দ করেছে।',
                'আবারও কিনবো ইনশাআল্লাহ।',
                'দুর্দান্ত! প্রত্যাশার চেয়েও ভালো।',
                'Top quality product. Very happy.',
                'Best purchase I have made this month.',
                'Genuine product, fast delivery. Thanks seller!',
                'Superb! Nothing to complain.',
                'The product is amazing. Worth every taka.',
                'Highly satisfied with the quality.',
                'Loved the fabric quality. Soft and durable.',
                'Stitching is neat and clean. Good job.',
                'সত্যিই ভালো মানের প্রোডাক্ট।',
                'দামের তুলনায় অসাধারণ।',
                'ফাস্ট ডেলিভারির জন্য ধন্যবাদ।',
            ],
            4 => [
                'Delivery was fast, product is good.',
                'Received in good condition. Thanks.',
                'Good value for money.',
                'Nice product. Slightly different shade but okay.',
                'Good quality, delivered on time.',
                'Satisfied with the purchase overall.',
                'Very nice, will recommend to friends.',
                'প্রোডাক্ট ভালো, তবে ডেলিভারি একটু দেরি হয়েছে।',
                'কোয়ালিটি ভালো, প্যাকেজিং আরও ভালো হতে পারতো।',
                'দাম ঠিক আছে, প্রোডাক্ট ভালো।',
                'Good product. Not the best but worth it.',
                'Reasonably good quality.',
            ],
            3 => [
                'Nice product but packaging could be better.',
                'Quality is decent, not the best but okay.',
                'Product is fine, delivery was a bit slow.',
                'Just okay. Nothing special.',
                'Good but slightly different from the picture.',
                'Average quality. Expected better.',
                'মোটামুটি ভালো, তবে ছবির সাথে কিছুটা অমিল।',
                'ঠিক আছে, খুব একটা বিশেষ কিছু না।',
                'ডেলিভারি দেরিতে এসেছে, প্রোডাক্ট ঠিক আছে।',
                'Okay for the price.',
                'Decent product, slow delivery.',
                'Color slightly faded compared to picture.',
            ],
            2 => [
                'Not as described. Slightly disappointed.',
                'Quality is below average.',
                'Delivery was very late.',
                'Product does not match the picture.',
                'প্রোডাক্ট ভালো না, ছবির সাথে মেলেনি।',
                'ডেলিভারি অনেক দেরি করেছে।',
                'Not worth the money.',
                'Smaller than expected. Disappointed.',
                'Product got damaged during delivery.',
            ],
            1 => [
                'Very poor quality. Do not buy.',
                'Worst experience. Product is broken.',
                'Received wrong item. Very upset.',
                'একদম খারাপ প্রোডাক্ট, রিটার্ন করতে চাই।',
                'ভুল প্রোডাক্ট পাঠিয়েছে, খুব খারাপ লেগেছে।',
                'টাকা নষ্ট। কেউ কিনবেন না।',
                'Terrible quality. Never buying again.',
                'Total waste of money.',
            ],
        ];

        $ratingPool = [5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 4, 4, 4, 3, 3, 2, 1];

        $products = Product::select('id')->get();

        $bar = $this->command->getOutput()->createProgressBar($products->count());
        $bar->start();

        $this->command->info('⭐ Seeding reviews (random 5–15 per product)…');
        $this->command->newLine();

        $totalCreated = 0;

        foreach ($products as $product) {
            $targetReviews = rand(5, 18);

            $existing = Review::where('product_id', $product->id)->count();
            $needed   = $targetReviews - $existing;

            if ($needed <= 0) {
                $bar->advance();
                continue;
            }
            $pool = $userIds;
            shuffle($pool);
            $assignedUsers = array_slice($pool, 0, $needed);

            // If not enough users, pad with nulls (guest reviews)
            while (count($assignedUsers) < $needed) {
                $assignedUsers[] = null;
            }

            $rows = [];
            foreach ($assignedUsers as $userId) {
                $rating  = $ratingPool[array_rand($ratingPool)];
                $comment = $commentsByRating[$rating][array_rand($commentsByRating[$rating])];

                $rows[] = [
                    'product_id' => $product->id,
                    'user_id'    => $userId,
                    'rating'     => $rating,
                    'comment'    => $comment,
                    'status'     => true,
                    'is_read'    => true,
                    'created_at' => now()->subDays(rand(1, 120))->subMinutes(rand(0, 1440)),
                    'updated_at' => now(),
                ];
            }

            Review::insert($rows);
            $totalCreated += count($rows);
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('Done!');
        $this->command->table(
            ['Metric', 'Value'],
            [
                ['Customer users',  count($userIds)],
                ['Products',        Product::count()],
                ['New reviews',     $totalCreated],
                ['Total reviews',   Review::count()],
                ['Avg rating',      round(Review::avg('rating'), 2)],
                ['Min per product', Review::selectRaw('COUNT(*) c')->groupBy('product_id')->orderBy('c')->value('c') ?? 0],
                ['Max per product', Review::selectRaw('COUNT(*) c')->groupBy('product_id')->orderByDesc('c')->value('c') ?? 0],
            ]
        );
    }
}
