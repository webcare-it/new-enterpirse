<?php

namespace Database\Seeders;

use App\Models\Search;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing records (optional)
        // Search::truncate();

        // Predefined search terms with their popularity weights
        $searchTermsWithWeight = [
            // High popularity (weight 10)
            ['term' => 'laptop', 'weight' => 10],
            ['term' => 'smartphone', 'weight' => 10],
            ['term' => 'headphones', 'weight' => 10],
            ['term' => 'smartwatch', 'weight' => 10],
            ['term' => 't-shirt', 'weight' => 10],
            ['term' => 'jeans', 'weight' => 10],
            ['term' => 'shoes', 'weight' => 10],
            ['term' => 'sneakers', 'weight' => 10],
            ['term' => 'tv', 'weight' => 10],
            ['term' => 'refrigerator', 'weight' => 10],

            // Medium popularity (weight 6)
            ['term' => 'tablet', 'weight' => 6],
            ['term' => 'camera', 'weight' => 6],
            ['term' => 'speaker', 'weight' => 6],
            ['term' => 'keyboard', 'weight' => 6],
            ['term' => 'mouse', 'weight' => 6],
            ['term' => 'monitor', 'weight' => 6],
            ['term' => 'jacket', 'weight' => 6],
            ['term' => 'hoodie', 'weight' => 6],
            ['term' => 'dress', 'weight' => 6],
            ['term' => 'microwave', 'weight' => 6],
            ['term' => 'oven', 'weight' => 6],
            ['term' => 'blender', 'weight' => 6],
            ['term' => 'washing machine', 'weight' => 6],
            ['term' => 'air conditioner', 'weight' => 6],
            ['term' => 'books', 'weight' => 6],
            ['term' => 'novel', 'weight' => 6],
            ['term' => 'treadmill', 'weight' => 6],
            ['term' => 'dumbbells', 'weight' => 6],
            ['term' => 'shampoo', 'weight' => 6],
            ['term' => 'perfume', 'weight' => 6],
            ['term' => 'sofa', 'weight' => 6],
            ['term' => 'bed', 'weight' => 6],
            ['term' => 'lego', 'weight' => 6],
            ['term' => 'video game', 'weight' => 6],
            ['term' => 'pen', 'weight' => 6],
            ['term' => 'notebook', 'weight' => 6],

            // Low popularity (weight 3)
            ['term' => 'printer', 'weight' => 3],
            ['term' => 'scanner', 'weight' => 3],
            ['term' => 'hard drive', 'weight' => 3],
            ['term' => 'ssd', 'weight' => 3],
            ['term' => 'ram', 'weight' => 3],
            ['term' => 'graphics card', 'weight' => 3],
            ['term' => 'shorts', 'weight' => 3],
            ['term' => 'skirt', 'weight' => 3],
            ['term' => 'pants', 'weight' => 3],
            ['term' => 'socks', 'weight' => 3],
            ['term' => 'boots', 'weight' => 3],
            ['term' => 'sandals', 'weight' => 3],
            ['term' => 'hat', 'weight' => 3],
            ['term' => 'cap', 'weight' => 3],
            ['term' => 'scarf', 'weight' => 3],
            ['term' => 'gloves', 'weight' => 3],
            ['term' => 'belt', 'weight' => 3],
            ['term' => 'dishwasher', 'weight' => 3],
            ['term' => 'coffee maker', 'weight' => 3],
            ['term' => 'toaster', 'weight' => 3],
            ['term' => 'vacuum cleaner', 'weight' => 3],
            ['term' => 'hair dryer', 'weight' => 3],
            ['term' => 'iron', 'weight' => 3],
            ['term' => 'comic', 'weight' => 3],
            ['term' => 'magazine', 'weight' => 3],
            ['term' => 'textbook', 'weight' => 3],
            ['term' => 'dictionary', 'weight' => 3],
            ['term' => 'cookbook', 'weight' => 3],
            ['term' => 'yoga mat', 'weight' => 3],
            ['term' => 'fitness tracker', 'weight' => 3],
            ['term' => 'sports shoes', 'weight' => 3],
            ['term' => 'football', 'weight' => 3],
            ['term' => 'basketball', 'weight' => 3],
            ['term' => 'tennis racket', 'weight' => 3],
            ['term' => 'cricket bat', 'weight' => 3],
            ['term' => 'soap', 'weight' => 3],
            ['term' => 'lotion', 'weight' => 3],
            ['term' => 'deodorant', 'weight' => 3],
            ['term' => 'toothpaste', 'weight' => 3],
            ['term' => 'toothbrush', 'weight' => 3],
            ['term' => 'makeup', 'weight' => 3],
            ['term' => 'lipstick', 'weight' => 3],
            ['term' => 'table', 'weight' => 3],
            ['term' => 'chair', 'weight' => 3],
            ['term' => 'lamp', 'weight' => 3],
            ['term' => 'curtain', 'weight' => 3],
            ['term' => 'carpet', 'weight' => 3],
            ['term' => 'pillow', 'weight' => 3],
            ['term' => 'blanket', 'weight' => 3],
            ['term' => 'towel', 'weight' => 3],
            ['term' => 'puzzle', 'weight' => 3],
            ['term' => 'board game', 'weight' => 3],
            ['term' => 'chess', 'weight' => 3],
            ['term' => 'monopoly', 'weight' => 3],
            ['term' => 'playstation', 'weight' => 3],
            ['term' => 'xbox', 'weight' => 3],
            ['term' => 'drone', 'weight' => 3],
            ['term' => 'pencil', 'weight' => 3],
            ['term' => 'eraser', 'weight' => 3],
            ['term' => 'marker', 'weight' => 3],
            ['term' => 'backpack', 'weight' => 3],
            ['term' => 'bag', 'weight' => 3],
        ];

        $searches = [];
        $timestamp = Carbon::now();

        // Generate weighted search terms list
        $weightedTerms = [];
        foreach ($searchTermsWithWeight as $item) {
            for ($i = 0; $i < $item['weight']; $i++) {
                $weightedTerms[] = $item['term'];
            }
        }

        // Generate 200 unique search records with realistic data
        for ($i = 0; $i < 200; $i++) {
            // Select random term based on weight
            $baseTerm = $weightedTerms[array_rand($weightedTerms)];

            // Add variety to terms
            $variationType = rand(1, 8);
            switch ($variationType) {
                case 1:
                    $query = $baseTerm;
                    break;
                case 2:
                    $query = 'best ' . $baseTerm;
                    break;
                case 3:
                    $query = 'cheap ' . $baseTerm;
                    break;
                case 4:
                    $query = 'buy ' . $baseTerm . ' online';
                    break;
                case 5:
                    $query = $baseTerm . ' price';
                    break;
                case 6:
                    $query = $baseTerm . ' ' . rand(2020, 2024);
                    break;
                case 7:
                    $query = ucfirst($baseTerm);
                    break;
                case 8:
                    $query = strtoupper($baseTerm);
                    break;
                default:
                    $query = $baseTerm;
                    break;
            }

            // Random date distribution (more recent = higher chance)
            $daysAgo = $this->getWeightedRandomDays();
            $createdAt = Carbon::now()->subDays($daysAgo)->subMinutes(rand(0, 1439));
            $updatedAt = clone $createdAt;

            // 40% chance of being searched again later
            if (rand(1, 100) <= 40) {
                $updatedAt = $createdAt->copy()->addDays(rand(1, $daysAgo > 0 ? $daysAgo : 1));
            }

            // Search count based on popularity and recency
            $baseCount = rand(5, 100);
            $recencyBonus = $daysAgo < 7 ? rand(20, 50) : ($daysAgo < 30 ? rand(5, 20) : 0);
            $count = $baseCount + $recencyBonus;

            $searches[] = [
                'query' => $query,
                'count' => $count,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];
        }

        // Remove duplicates by query (keep the one with higher count)
        $uniqueSearches = [];
        foreach ($searches as $search) {
            $key = strtolower($search['query']);
            if (!isset($uniqueSearches[$key]) || $uniqueSearches[$key]['count'] < $search['count']) {
                $uniqueSearches[$key] = $search;
            }
        }

        // Insert unique searches
        $finalSearches = array_values($uniqueSearches);

        // Insert in chunks
        foreach (array_chunk($finalSearches, 50) as $chunk) {
            Search::insert($chunk);
        }

        $this->command->info('200 search records seeded successfully!');
        $this->command->info('Unique keywords: ' . count($finalSearches));
    }

    /**
     * Get weighted random days (more recent dates have higher probability)
     */
    private function getWeightedRandomDays(): int
    {
        $random = rand(1, 100);

        if ($random <= 40) {
            // Last 7 days
            return rand(0, 7);
        } elseif ($random <= 65) {
            // Last 30 days
            return rand(8, 30);
        } elseif ($random <= 80) {
            // Last 90 days
            return rand(31, 90);
        } else {
            // Older than 90 days
            return rand(91, 180);
        }
    }
}
