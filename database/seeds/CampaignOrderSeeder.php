<?php

namespace Database\Seeders;

use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\ProductInventory;
use App\Models\Admin\ProductVarient;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CampaignOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting to create 500 orders from last year...');

        $products = Product::with(['variants', 'inventory'])->get();

        if ($products->isEmpty()) {
            $this->command->error('No products found! Please add products first.');
            return;
        }

        $productsArray = $products->toArray();
        $productsCount = count($productsArray);

        $users = $this->getUsers();
        $usersArray = $users->toArray();
        $usersCount = count($usersArray);

        $totalOrders = 500;
        $ordersCreated = 0;

        DB::beginTransaction();

        try {
            for ($i = 0; $i < $totalOrders; $i++) {
                $randomDaysAgo = rand(0, 365);
                $randomDate = Carbon::now()->subDays($randomDaysAgo)->setTime(rand(0, 23), rand(0, 59), rand(0, 59));
                $randomUserIndex = rand(0, $usersCount - 1);
                $user = $users[$randomUserIndex];
                $paymentTypes = ['cash_on_delivery', 'card', 'bkash', 'nagad', 'manual'];
                $paymentType = $paymentTypes[array_rand($paymentTypes)];
                $deliveryStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
                $deliveryStatus = $deliveryStatuses[array_rand($deliveryStatuses)];
                $paymentStatuses = ['unpaid', 'paid', 'partial', 'refunded'];
                $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
                $hasDiscount = rand(0, 100) > 70; // 30% chance of discount
                $couponDiscount = 0;
                $manualDiscount = 0;
                if ($hasDiscount) {
                    if (rand(0, 1)) {
                        $couponDiscount = rand(0, 20) * 10; // 0-200
                    } else {
                        $manualDiscount = rand(0, 500); // 0-500
                    }
                }
                $numItems = rand(1, min(5, $productsCount));
                $randomProductKeys = array_rand($productsArray, $numItems);

                if (!is_array($randomProductKeys)) {
                    $randomProductKeys = [$randomProductKeys];
                }

                $subtotal = 0;
                $orderItems = [];

                foreach ($randomProductKeys as $key) {
                    $product = $products[$key];
                    $quantity = rand(1, 3);
                    $hasVariant = $product->variants && $product->variants->count() > 0;

                    $variantId = null;
                    $price = $product->price->regular_price ?? 100;
                    $variantAttributes = null;

                    if ($hasVariant && rand(0, 1)) {
                        $variantsArray = $product->variants->toArray();
                        if (!empty($variantsArray)) {
                            $randomVariantKey = array_rand($variantsArray);
                            $variant = $product->variants[$randomVariantKey];
                            $variantId = $variant->id;
                            $price = $variant->price ?? $price;
                            $variantAttributes = json_encode([
                                'size' => $variant->size ?? 'M',
                                'color' => $variant->color ?? 'Default'
                            ]);
                        }
                    }

                    $itemTotal = $price * $quantity;
                    $subtotal += $itemTotal;

                    $orderItems[] = [
                        'product_id' => $product->id,
                        'variant_id' => $variantId,
                        'variation' => $variantAttributes,
                        'price' => $price,
                        'quantity' => $quantity,
                        'has_variant' => $hasVariant
                    ];
                }

                $totalDiscount = $couponDiscount + $manualDiscount;
                $grandTotal = max(0, $subtotal - $totalDiscount);
                $orderCode = $this->generateOrderCode($randomDate, $i);

                $order = Order::create([
                    'user_id' => $user->id,
                    'shipping_address' => $this->getRandomAddress(),
                    'delivery_status' => $deliveryStatus,
                    'payment_type' => $paymentType,
                    'manual_payment' => $paymentType == 'manual',
                    'payment_status' => $paymentStatus,
                    'grand_total' => $grandTotal,
                    'shipping_cost' => rand(0, 100), // Random shipping cost
                    'coupon_discount' => $couponDiscount,
                    'discount' => $manualDiscount,
                    'code' => $orderCode,
                    'notes' => $this->getRandomNotes(),
                    'date' => $randomDate->timestamp,
                    'viewed' => rand(0, 1),
                    'delivery_viewed' => rand(0, 1),
                    'payment_status_viewed' => rand(0, 1),
                    'commission_calculated' => rand(0, 1),
                    'shipping_type' => 'home_delivery',
                    'order_type' => 'physical',
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate
                ]);

                foreach ($orderItems as $item) {
                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'variation' => $item['variation'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'tax' => rand(0, 50), // Random tax
                        'shipping_cost' => rand(0, 50), // Random shipping cost per item
                        'payment_status' => $paymentStatus,
                        'delivery_status' => $deliveryStatus,
                        'shipping_type' => 'home_delivery',
                        'created_at' => $randomDate,
                        'updated_at' => $randomDate
                    ]);
                    $this->updateStock($item['product_id'], $item['variant_id'], $item['quantity']);
                }

                $ordersCreated++;
                if ($ordersCreated % 50 == 0) {
                    $this->command->info("Created {$ordersCreated} / {$totalOrders} orders");
                }
            }

            DB::commit();

            $this->command->info("✅ Successfully created {$ordersCreated} orders from last year!");

            $this->displayStatistics();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error: ' . $e->getMessage());
            $this->command->error('Line: ' . $e->getLine());
            $this->command->error('File: ' . $e->getFile());
        }
    }

    /**
     * Get or create test users
     */
    private function getUsers()
    {
        $users = User::where('user_type', 'customer')->get();
        if ($users->count() < 20) {
            for ($i = 1; $i <= 20; $i++) {
                User::create([
                    'name' => "Test Customer {$i}",
                    'email' => "customer{$i}@example.com",
                    'phone' => "01" . rand(10000000, 99999999),
                    'password' => bcrypt('password123'),
                    'user_type' => 'customer',
                    'email_verified_at' => now()
                ]);
            }
            $users = User::where('user_type', 'customer')->get();
        }
        return $users;
    }

    /**
     * Generate unique order code
     */
    private function generateOrderCode($date, $counter)
    {
        return 'ORD-' . $date->format('Y') . $date->format('m') . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get random shipping address
     */
    private function getRandomAddress()
    {
        $addresses = [
            '123 Main Street, Dhaka-1212, Bangladesh',
            '45 Park Avenue, Chittagong, Bangladesh',
            '78 Lake Road, Sylhet, Bangladesh',
            '90 Hill View, Rajshahi, Bangladesh',
            '12 Beach Road, Cox\'s Bazar, Bangladesh',
            '34 Garden Street, Khulna, Bangladesh',
            '56 New Market, Barishal, Bangladesh',
            '89 Old Town, Rangpur, Bangladesh',
            '67 Central Road, Mymensingh, Bangladesh',
            '23 Commercial Area, Narayanganj, Bangladesh',
            '101 Housing Estate, Gazipur, Bangladesh',
            '202 Industrial Zone, Savar, Bangladesh',
            '303 VIP Road, Comilla, Bangladesh',
            '404 Lake Drive, Jessore, Bangladesh',
            '505 Ocean Boulevard, Bogura, Bangladesh'
        ];

        return $addresses[array_rand($addresses)];
    }

    /**
     * Get random notes
     */
    private function getRandomNotes()
    {
        $notes = [
            null,
            'Please call before delivery',
            'Handle with care',
            'Gift wrapping required',
            'Delivery between 10 AM - 5 PM',
            'Leave at the security desk',
            'Ring the doorbell twice',
            'Fragile items - handle carefully',
            'Express delivery requested',
            'Birthday gift - please wrap nicely'
        ];

        $randomNote = $notes[array_rand($notes)];
        return $randomNote;
    }

    /**
     * Update product stock
     */
    private function updateStock($productId, $variantId, $quantity)
    {
        if ($variantId) {
            $variant = ProductVarient::find($variantId);
            if ($variant) {
                $variant->decrement('quantity', $quantity);
            }
        } else {
            $inventory = ProductInventory::where('product_id', $productId)->first();
            if ($inventory) {
                $inventory->decrement('stock', $quantity);
            }
        }
    }

    /**
     * Display order statistics
     */
    private function displayStatistics()
    {
        $ordersCount = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('grand_total');
        $avgOrderValue = $ordersCount > 0 ? $totalRevenue / $ordersCount : 0;

        $this->command->info("Order Statistics (Last Year):");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("Total Orders: {$ordersCount}");
        $this->command->info("Total Revenue: ৳" . number_format($totalRevenue, 2));
        $this->command->info("Average Order Value: ৳" . number_format($avgOrderValue, 2));

        $monthlyOrders = Order::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(grand_total) as revenue')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $this->command->info("Monthly Orders Breakdown:");
        foreach ($monthlyOrders as $stat) {
            $monthName = Carbon::createFromDate($stat->year, $stat->month, 1)->format('F Y');
            $this->command->info("  {$monthName}: {$stat->count} orders (৳" . number_format($stat->revenue, 2) . ")");
        }

        $statusStats = Order::select('delivery_status', DB::raw('count(*) as count'))
            ->groupBy('delivery_status')
            ->get();

        $this->command->info("Orders by Delivery Status:");
        foreach ($statusStats as $stat) {
            $percentage = ($stat->count / $ordersCount) * 100;
            $this->command->info("  {$stat->delivery_status}: {$stat->count} orders (" . round($percentage, 2) . "%)");
        }

        $paymentStats = Order::select('payment_status', DB::raw('count(*) as count'))
            ->groupBy('payment_status')
            ->get();

        $this->command->info("Orders by Payment Status:");
        foreach ($paymentStats as $stat) {
            $percentage = ($stat->count / $ordersCount) * 100;
            $this->command->info("  {$stat->payment_status}: {$stat->count} orders (" . round($percentage, 2) . "%)");
        }

        $oldestOrder = Order::orderBy('created_at', 'asc')->first();
        $newestOrder = Order::orderBy('created_at', 'desc')->first();

        if ($oldestOrder && $newestOrder) {
            $daysDiff = $oldestOrder->created_at->diffInDays($newestOrder->created_at);
            $dailyAvg = $daysDiff > 0 ? $ordersCount / $daysDiff : $ordersCount;
            $this->command->info("Daily Average Orders: " . round($dailyAvg, 2) . " orders/day");
            $this->command->info("Date Range: " . $oldestOrder->created_at->format('Y-m-d') . " to " . $newestOrder->created_at->format('Y-m-d'));
        }
    }
}
