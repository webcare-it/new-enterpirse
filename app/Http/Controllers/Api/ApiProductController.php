<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Admin\Product;
use Illuminate\Http\Request;
use App\Models\Admin\Attribute;
use App\Models\Admin\Campaign;
use App\Models\Admin\Category;
use App\Models\Admin\Color;
use App\Models\Admin\Review;
use App\Models\BusinessSetting;
use App\Models\User;
use Log;

class ApiProductController extends Controller
{
    // public function index(Request $request)
    // {
    //     $perPage = min(max((int) $request->get('per_page', 15), 1), 100);
    //     $page = max((int) $request->get('page', 1), 1);

    //     $minPrice = $request->filled('min_price') ? (float) $request->get('min_price') : null;
    //     $maxPrice = $request->filled('max_price') ? (float) $request->get('max_price') : null;
    //     $rating = $request->filled('rating') ? (float) $request->get('rating') : null;
    //     $sort = $request->get('sort', 'select');
    //     $brands = $request->filled('brands')
    //         ? array_values(array_unique(array_filter(array_map(fn($value) => trim($value), explode(',', $request->get('brands'))))))
    //         : [];

    //     $products = Product::query()
    //         ->with([
    //             'variants' => fn($query) => $query->select('id', 'product_id', 'price', 'wholesale_price', 'attribute', 'attribute_value', 'color'),
    //             'brand' => fn($query) => $query->select('id', 'name'),
    //             'price' => fn($query) => $query->select('product_id', 'regular_price', 'sale_price', 'wholesale_price'), // added wholesale_price
    //             'inventory' => fn($query) => $query->select('product_id', 'stock'),
    //             'reviews' => fn($query) => $query->where('status', 1),
    //         ])
    //         ->select([
    //             'id',
    //             'name',
    //             'slug',
    //             'thumbnail',
    //             'brand_id',
    //             'num_of_sale',
    //             'status',
    //             'is_published',
    //             'is_variant',
    //             'created_at',
    //             'updated_at',
    //         ])
    //         ->where('is_published', 1)
    //         ->get();

    //     // Filter & sort (unchanged)
    //     $filteredProducts = $products->filter(function ($product) use ($minPrice, $maxPrice, $rating, $brands) {
    //         $price = (float) ($product->price?->sale_price ?? $product->price?->regular_price ?? 0);

    //         if ($minPrice !== null && $price < $minPrice) {
    //             return false;
    //         }

    //         if ($maxPrice !== null && $price > $maxPrice) {
    //             return false;
    //         }

    //         $productRating = $product->reviews->isNotEmpty()
    //             ? round((float) $product->reviews->avg('rating'), 1)
    //             : 0;

    //         if ($rating !== null && $productRating < $rating) {
    //             return false;
    //         }

    //         if ($brands !== []) {
    //             $brandNames = array_map('strtolower', $brands);
    //             $productBrand = strtolower((string) ($product->brand->name ?? ''));

    //             if (! in_array($productBrand, $brandNames, true)) {
    //                 return false;
    //             }
    //         }

    //         return true;
    //     })->values();

    //     $filteredProducts = $filteredProducts->sortBy(function ($product) use ($sort) {
    //         $price = (float) ($product->price?->sale_price ?? $product->price?->regular_price ?? 0);

    //         return match ($sort) {
    //             'newest' => - ($product->id ?? 0),
    //             'oldest' => $product->id ?? 0,
    //             'price-low' => $price,
    //             'price-high' => -$price,
    //             default => - ($product->id ?? 0),
    //         };
    //     }, SORT_REGULAR)->values();

    //     $total = $filteredProducts->count();
    //     $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 0;
    //     $currentPage = min($page, $totalPages ?: 1);
    //     $pagedProducts = $filteredProducts->slice(($currentPage - 1) * $perPage, $perPage)->values();

    //     // ---- Add wholesale_price & price_range to paged products ----
    //     $items = ProductResource::collection($pagedProducts)->resolve();
    //     $models = $pagedProducts; // already a collection of Product models



    //     foreach ($items as $index => &$item) {
    //         $model = $models->firstWhere('id', $item['id']);
    //         if (! $model) {
    //             $item['wholesale_price'] = null;
    //             unset($item['price_range']);
    //             continue;
    //         }

    //         if ($model->variants->isNotEmpty()) {
    //             // Variant product → max wholesale among variants
    //             $item['wholesale_price'] = (float) $model->variants->max('wholesale_price');

    //             // Price range from variant retail prices
    //             $prices = $model->variants->pluck('price')->map(fn($p) => (float) $p);
    //             $item['price_range'] = [
    //                 'min' => $prices->min(),
    //                 'max' => $prices->max(),
    //             ];
    //         } else {
    //             // Simple product → fallback to parent wholesale_price
    //             $item['wholesale_price'] = $model->price ? (float) $model->price->wholesale_price : null;
    //             unset($item['price_range']); // or set to null if you prefer
    //         }
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => [
    //             'products' => $items,
    //             'pagination' => [
    //                 'current_page' => $currentPage,
    //                 'per_page' => $perPage,
    //                 'total' => $total,
    //                 'total_pages' => $totalPages,
    //                 'has_more' => $currentPage < $totalPages,
    //             ],
    //         ],
    //     ]);
    // }

    public function index(Request $request)
    {
        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);
        $page = max((int) $request->get('page', 1), 1);

        $minPrice = $request->filled('min_price') ? (float) $request->get('min_price') : null;
        $maxPrice = $request->filled('max_price') ? (float) $request->get('max_price') : null;
        $rating = $request->filled('rating') ? (float) $request->get('rating') : null;
        $sort = $request->get('sort', 'select');
        $brands = $request->filled('brands')
            ? array_values(array_unique(array_filter(array_map(fn($value) => trim($value), explode(',', $request->get('brands'))))))
            : [];

        $products = Product::query()
            ->with([
                'variants' => fn($query) => $query->select('id', 'product_id', 'price', 'wholesale_price', 'attribute', 'attribute_value', 'color'),
                'brand' => fn($query) => $query->select('id', 'name'),
                'price' => fn($query) => $query->select('product_id', 'regular_price', 'sale_price', 'wholesale_price'), // added wholesale_price
                'inventory' => fn($query) => $query->select('product_id', 'stock'),
                'reviews' => fn($query) => $query->where('status', 1),
            ])
            ->select([
                'id',
                'name',
                'slug',
                'thumbnail',
                'brand_id',
                'num_of_sale',
                'status',
                'is_published',
                'is_variant',
                'created_at',
                'updated_at',
            ])
            ->where('is_published', 1)
            ->get();

        // Filter & sort (unchanged)
        $filteredProducts = $products->filter(function ($product) use ($minPrice, $maxPrice, $rating, $brands) {
            $price = (float) ($product->price?->sale_price ?? $product->price?->regular_price ?? 0);

            if ($minPrice !== null && $price < $minPrice) {
                return false;
            }

            if ($maxPrice !== null && $price > $maxPrice) {
                return false;
            }

            $productRating = $product->reviews->isNotEmpty()
                ? round((float) $product->reviews->avg('rating'), 1)
                : 0;

            if ($rating !== null && $productRating < $rating) {
                return false;
            }

            if ($brands !== []) {
                $brandNames = array_map('strtolower', $brands);
                $productBrand = strtolower((string) ($product->brand->name ?? ''));

                if (! in_array($productBrand, $brandNames, true)) {
                    return false;
                }
            }

            return true;
        })->values();

        $filteredProducts = $filteredProducts->sortBy(function ($product) use ($sort) {
            $price = (float) ($product->price?->sale_price ?? $product->price?->regular_price ?? 0);

            return match ($sort) {
                'newest' => - ($product->id ?? 0),
                'oldest' => $product->id ?? 0,
                'price-low' => $price,
                'price-high' => -$price,
                default => - ($product->id ?? 0),
            };
        }, SORT_REGULAR)->values();

        $total = $filteredProducts->count();
        $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 0;
        $currentPage = min($page, $totalPages ?: 1);
        $pagedProducts = $filteredProducts->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // ---- Add wholesale_price & price_range to paged products ----
        $items = ProductResource::collection($pagedProducts)->resolve();
        $models = $pagedProducts; // already a collection of Product models

        foreach ($items as $index => &$item) {
            $model = $models->firstWhere('id', $item['id']);
            if (! $model) {
                $item['wholesale_price'] = null;
                unset($item['price_range']);
                continue;
            }

            if ($model->variants->isNotEmpty()) {
                // Variant product → max wholesale among variants
                $item['wholesale_price'] = (float) $model->variants->max('wholesale_price');

                // Price range from variant retail prices
                $prices = $model->variants->pluck('price')->map(fn($p) => (float) $p);
                $item['price_range'] = [
                    'min' => $prices->min(),
                    'max' => $prices->max(),
                ];
            } else {
                // Simple product → fallback to parent wholesale_price
                $item['wholesale_price'] = $model->price ? (float) $model->price->wholesale_price : null;
                unset($item['price_range']); // or set to null if you prefer
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $items, // now enriched
                'pagination' => [
                    'current_page' => $currentPage,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages,
                    'has_more' => $currentPage < $totalPages,
                ],
            ],
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $categorySlug = $request->input('c', '');
        $sort = $request->input('sort', 'newest');
        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 10);

        $productsQuery = Product::where('status', 1)
            ->where('is_published', 1)
            ->with([
                'brand' => fn($q) => $q->select('id', 'name'),
                'price' => fn($q) => $q->select(
                    'product_id',
                    'regular_price',
                    'sale_price',
                    'discount',
                    'discount_type',
                    'wholesale_price' // added
                ),
                'inventory' => fn($q) => $q->select('product_id', 'stock'),
                'reviews' => fn($q) => $q->where('status', 1),
                'variants' => fn($q) => $q->select('id', 'product_id', 'price', 'wholesale_price'), // added price & wholesale_price
            ])
            ->select([
                'id',
                'name',
                'slug',
                'thumbnail',
                'brand_id',
                'num_of_sale',
                'status',
                'is_published',
                'is_variant',
                'created_at',
                'updated_at',
            ]);

        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $productsQuery->where('category_id', $category->id);
            }
        }

        if (!empty($query)) {
            $productsQuery->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhere('short_description', 'LIKE', "%{$query}%")
                    ->orWhere('slug', 'LIKE', "%{$query}%");
            });
            $productsQuery->orderByRaw("
            CASE
                WHEN name = ? THEN 1
                WHEN name LIKE ? THEN 2
                WHEN name LIKE ? THEN 3
                ELSE 4
            END
        ", [$query, $query . '%', '%' . $query . '%']);
        }

        $products = $productsQuery->get();

        // ---- Sorting ----
        $sorted = $products->sortBy(function ($product) use ($sort) {
            $price = (float) ($product->price?->sale_price ?? $product->price?->regular_price ?? 0);
            return match ($sort) {
                'newest'      => - ($product->id ?? 0),
                'oldest'      => $product->id ?? 0,
                'price-low'   => $price,
                'price-high'  => -$price,
                default       => - ($product->id ?? 0),
            };
        }, SORT_REGULAR)->values();

        // ---- Pagination ----
        $total = $sorted->count();
        $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 0;
        $currentPage = min($page, $totalPages ?: 1);
        $paged = $sorted->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // ---- Enrich with wholesale_price and price_range ----
        $items = ProductResource::collection($paged)->resolve();
        $models = $paged; // collection of Product models

        foreach ($items as $index => &$item) {
            $model = $models->firstWhere('id', $item['id']);
            if (! $model) {
                $item['wholesale_price'] = null;
                unset($item['price_range']);
                continue;
            }

            if ($model->variants->isNotEmpty()) {
                // Variant product → max wholesale among variants
                $item['wholesale_price'] = (float) $model->variants->max('wholesale_price');

                // Price range from variant retail prices
                $prices = $model->variants->pluck('price')->map(fn($p) => (float) $p);
                $item['price_range'] = [
                    'min' => $prices->min(),
                    'max' => $prices->max(),
                ];
            } else {
                // Simple product → fallback to parent wholesale_price
                $item['wholesale_price'] = $model->price ? (float) $model->price->wholesale_price : null;
                unset($item['price_range']);
            }
        }

        // ---- Response ----
        return response()->json([
            'success' => true,
            'data' => [
                'products'   => $items, // enriched array
                'pagination' => [
                    'current_page' => $currentPage,
                    'per_page'     => $perPage,
                    'total'        => $total,
                    'total_pages'  => $totalPages,
                    'has_more'     => $currentPage < $totalPages,
                ],
            ],
        ]);
    }


    public function productDetails(Request $request, $identifier)
    {
        try {
            $query = Product::with([
                'inventory',
                'price',
                'variants.attributeRel',
                'category',
                'brand',
                'reviews'
            ])->where('is_published', 1);

            if (is_numeric($identifier)) {
                $query->where('id', $identifier);
            } else {
                $query->where('slug', $identifier);
            }

            $product = $query->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            $discount_amount = $product->price->discount ?? 0;

            $galleryImages = [];
            if ($product->photos) {
                $photoIds = json_decode($product->photos, true);
                if (is_array($photoIds)) {
                    foreach ($photoIds as $id) {
                        if ($id == $product->thumbnail) {
                            continue;
                        }
                        $galleryImages[] = uploaded_asset($id);
                    }
                }
            }

            $variants = $product->variants->map(function ($variant) {
                $discount_amount = $product->price->discount ?? 10;
                $attributeValue = json_decode($variant->attribute_value, true) ?? [];
                $product = Product::with('price')->find($variant->product_id);
                $discount_amount = $product->price->discount ?? 0;
                $newVarPrice = (float) $variant->price - (float) $discount_amount;
                return [
                    'id'              => $variant->id,
                    'sku'             => $variant->sku ?? 'N/A',
                    'price'           => $newVarPrice,
                    'stock'           => $variant->quantity ?? 0,
                    'image'           => $variant->image ? uploaded_asset($variant->image) : null,
                    'attribute_value' => $attributeValue,
                    'attribute_name'  => $variant->attributeRel?->name,
                ];
            })->values();

            $reviews = $product->reviews;
            $reviewData = [
                'rating' => round($product->reviews->avg('rating') ?? 0, 1),
                'reviews_count' => $product->reviews->count() ?? 0,
                'items'         => $reviews ? $reviews->map(function ($review) {
                    return [
                        'id'            => $review->id,
                        'rating'        => $review->rating,
                        'comment'       => $review->comment,
                        'user'          => [
                            'id'     => $review->user_id,
                            'name'   => $review->user->name ?? null,
                            'avatar' => $review->user->avatar ?? null,
                        ],
                        'created_at'    => $review->created_at->toDateTimeString(),
                    ];
                })->values() : []
            ];

            $formattedProduct = [
                'id'                => $product->id,
                'name'              => $product->name,
                'slug'              => $product->slug,
                'description'       => $product->description,
                'short_description' => $product->short_description,
                'thumbnail'         => $product->thumbnail ? uploaded_asset($product->thumbnail) : null,
                'yt_video_id'       => $product->video_link ?? null,
                'images'            => $galleryImages,
                'category' => $product->category ? [
                    'id'    => $product->category->id,
                    'name'  => $product->category->category_name,
                    'slug'  => $product->category->slug,
                    'image' => $product->category->image ? uploaded_asset($product->category->image) : null,
                ] : null,

                'brand' => $product->brand ? [
                    'id'   => $product->brand->id,
                    'name' => $product->brand->name,
                    'slug' => $product->brand->slug ?? null,
                    'logo' => $product->brand->logo ? uploaded_asset($product->brand->logo) : null,
                ] : null,

                'price' => [
                    'regular'             => $product->price->regular_price ?? 0,
                    'sale'                => $product->price->sale_price ?? null,
                    'discount'            => $product->price->discount ?? 0,
                    'discount_percentage' => $this->calculateDiscountPercentage($product),
                    'current'             => $this->getCurrentPrice($product),
                ],

                'inventory' => [
                    'unit'         => $product->unit ?? '',
                    'sku'          => $product->inventory->sku ?? 'N/A',
                    'stock'        => $product->inventory->stock ?? 0,
                    'stock_status' => ($product->inventory->stock ?? 0) > 0 ? 'in_stock' : 'out_of_stock',
                    'total_sold'   => $product->num_of_sale ?? 0,
                ],

                'variants'     => $variants,
                'has_variants' => $product->variants->count() > 0,
                'review'       => $reviewData,
                'created_at'   => $product->created_at->toDateTimeString(),
                'updated_at'   => $product->updated_at->toDateTimeString(),
            ];

            $relatedProducts = [];
            if ($product->category_id) {
                $relatedProducts = Product::with(['price', 'inventory', 'category', 'brand'])
                    ->where('is_published', 1)
                    ->where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->limit(10)
                    ->get()
                    ->map(function ($rel) {
                        $regularPrice = (float) ($rel->price->regular_price ?? 0);
                        $salePrice    = $rel->price->sale_price ?? null;
                        $discount     = (float) ($rel->price->discount ?? 0);

                        // Current price calculation
                        $currentPrice = $salePrice !== null
                            ? (float) $salePrice - $discount
                            : $regularPrice - $discount;

                        // Determine discount type (flat or percentage)
                        $discountType = 'flat';
                        if ($rel->price && isset($rel->price->discount_type)) {
                            $discountType = $rel->price->discount_type;
                        }

                        return [
                            'id'              => $rel->id,
                            'name'            => $rel->name,
                            'slug'            => $rel->slug,
                            'price'           => $currentPrice,
                            'original'        => $regularPrice,
                            'discount'        => $discount,
                            'discount_type'   => $discountType,
                            'rating'          => round($rel->reviews->avg('rating') ?? 0, 1),
                            'reviews' => (int) ($rel->relationLoaded('reviews') ? $rel->reviews->count() : $rel->reviews()->count()),
                            'image'           => $rel->thumbnail ? uploaded_asset($rel->thumbnail) : null,
                            'sold'            => $rel->num_of_sale ?? 0,
                            'has_variants'    => $rel->variants()->exists(),
                            'in_stock'        => ($rel->inventory->stock ?? 0) > 0,
                            'category'        => $rel->category ? [
                                'id'   => $rel->category->id,
                                'name' => $rel->category->category_name,
                                'slug' => $rel->category->slug,
                            ] : null,
                            'brand'           => $rel->brand?->name,
                            'wholesale_price' => (float) ($rel->wholesale_price ?? 0),
                        ];
                    })
                    ->values();
            }


            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully',
                'data'    => [
                    'product'          => $formattedProduct,
                    'related_products' => $relatedProducts,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Product API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch product: ' . $e->getMessage(),
                'product' => null,
            ], 500);
        }
    }


    /**
     * Calculate discount percentage from regular and sale price.
     */
    private function calculateDiscountPercentage($product)
    {
        $regularPrice = $product->price->regular_price ?? 0;
        $salePrice = $product->price->sale_price ?? null;

        if ($salePrice && $salePrice < $regularPrice && $regularPrice > 0) {
            return round((($regularPrice - $salePrice) / $regularPrice) * 100);
        }
        return 0;
    }

    /**
     * Get the current price (sale if lower, else regular).
     */
    private function getCurrentPrice($product)
    {
        $regularPrice = $product->price->regular_price ?? 0;
        $salePrice = $product->price->sale_price ?? null;

        if ($salePrice && $salePrice < $regularPrice) {
            return $salePrice;
        }
        return $regularPrice;
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function category(Request $request)
    {
        $categories = \App\Models\Admin\Category::with('subCategories')->get();
        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }

    public function categoryProducts(Request $request, $slug)
    {
        $category = \App\Models\Admin\Category::where('slug', $slug)->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $perPage = min(max((int) $request->get('per_page', 12), 1), 100);
        $page = max((int) $request->get('page', 1), 1);

        $minPrice = $request->filled('min_price') ? (float) $request->get('min_price') : null;
        $maxPrice = $request->filled('max_price') ? (float) $request->get('max_price') : null;
        $rating = $request->filled('rating') ? (float) $request->get('rating') : null;
        $sort = $request->get('sort', 'select');
        $brands = $request->filled('brands')
            ? array_values(array_unique(array_filter(array_map(fn($value) => trim($value), explode(',', $request->get('brands'))))))
            : [];

        $products = Product::query()
            ->where('category_id', $category->id)
            ->with([
                'brand' => fn($query) => $query->select('id', 'name'),
                'price' => fn($query) => $query->select('product_id', 'regular_price', 'sale_price', 'wholesale_price'), // added
                'inventory' => fn($query) => $query->select('product_id', 'stock'),
                'reviews' => fn($query) => $query->where('status', 1),
                'variants' => fn($query) => $query->select('id', 'product_id', 'price', 'wholesale_price'), // added
            ])
            ->select([
                'id',
                'name',
                'slug',
                'thumbnail',
                'brand_id',
                'num_of_sale',
                'status',
                'is_published',
                'is_variant',
                'created_at',
                'updated_at',
            ])
            ->get();

        $filteredProducts = $products->filter(function ($product) use ($minPrice, $maxPrice, $rating, $brands) {
            $price = (float) ($product->price?->sale_price ?? $product->price?->regular_price ?? 0);

            if ($minPrice !== null && $price < $minPrice) {
                return false;
            }

            if ($maxPrice !== null && $price > $maxPrice) {
                return false;
            }

            $productRating = $product->reviews->isNotEmpty()
                ? round((float) $product->reviews->avg('rating'), 1)
                : 0;

            if ($rating !== null && $productRating < $rating) {
                return false;
            }

            if ($brands !== []) {
                $brandNames = array_map('strtolower', $brands);
                $productBrand = strtolower((string) ($product->brand->name ?? ''));

                if (! in_array($productBrand, $brandNames, true)) {
                    return false;
                }
            }

            return true;
        })->values();

        $filteredProducts = $filteredProducts->sortBy(function ($product) use ($sort) {
            $price = (float) ($product->price?->sale_price ?? $product->price?->regular_price ?? 0);

            return match ($sort) {
                'newest' => - ($product->id ?? 0),
                'oldest' => $product->id ?? 0,
                'price-low' => $price,
                'price-high' => -$price,
                default => - ($product->id ?? 0),
            };
        }, SORT_REGULAR)->values();

        $total = $filteredProducts->count();
        $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 0;
        $currentPage = min($page, $totalPages ?: 1);
        $pagedProducts = $filteredProducts->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // ---- Enrich with wholesale_price and price_range ----
        $items = ProductResource::collection($pagedProducts)->resolve();
        $models = $pagedProducts; // collection of Product models

        foreach ($items as $index => &$item) {
            $model = $models->firstWhere('id', $item['id']);
            if (! $model) {
                $item['wholesale_price'] = null;
                unset($item['price_range']);
                continue;
            }

            if ($model->variants->isNotEmpty()) {
                // Variant product → max wholesale among variants
                $item['wholesale_price'] = (float) $model->variants->max('wholesale_price');

                // Price range from variant retail prices
                $prices = $model->variants->pluck('price')->map(fn($p) => (float) $p);
                $item['price_range'] = [
                    'min' => $prices->min(),
                    'max' => $prices->max(),
                ];
            } else {
                // Simple product → fallback to parent wholesale_price
                $item['wholesale_price'] = $model->price ? (float) $model->price->wholesale_price : null;
                unset($item['price_range']);
            }
        }

        // Get subcategories for this category
        $subCategories = \App\Models\Admin\SubCategory::where('category_id', $category->id)
            ->select(['id', 'name', 'slug', 'image'])
            ->get()
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $items, // enriched array
                'pagination' => [
                    'current_page' => $currentPage,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages,
                    'has_more' => $currentPage < $totalPages,
                ],
                'sub_categories' => $subCategories
            ],
        ]);
    }
    public function categories()
    {
        $category = \App\Models\Admin\Category::orderBy('position', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'categories' => $category
            ],
        ]);
    }


    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subcategories($slug)
    {
        try {
            $subCategory = \App\Models\Admin\SubCategory::where('slug', $slug)->with('products')->first();
            if (!$subCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subcategory not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json([
            'success' => true,
            'data' => $subCategory,
        ]);
    }
}
