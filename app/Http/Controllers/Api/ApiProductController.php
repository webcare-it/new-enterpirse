<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Illuminate\Http\Request;
use Log;

class ApiProductController extends Controller
{
    /**
     * Price used by the min/max price filters and the price sorts:
     * sale price, else regular price, else 0 (read from the same first price row the `price` relation returns).
     */
    private const LISTING_PRICE_SQL = 'COALESCE((SELECT COALESCE(pp.sale_price, pp.regular_price) FROM product_prices pp WHERE pp.product_id = products.id ORDER BY pp.id LIMIT 1), 0)';

    /**
     * Approved-review rating rounded to 1 decimal, 0 when the product has no approved reviews.
     */
    private const LISTING_RATING_SQL = 'COALESCE((SELECT ROUND(AVG(r.rating), 1) FROM reviews r WHERE r.product_id = products.id AND r.status = 1), 0)';

    public function index(Request $request)
    {
        $perPage = min(max((int) $request->get('per_page', 15), 1), 100);
        $page = max((int) $request->get('page', 1), 1);

        $query = Product::query()->where('is_published', 1);
        $this->applyListingFilters($query, $request);
        $this->applyListingSort($query, $request->get('sort', 'select'));

        [$items, $pagination] = $this->paginateListing($query, $page, $perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $items,
                'pagination' => $pagination,
            ],
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $categorySlug = $request->input('c', '');
        $sort = $request->input('sort', 'newest');
        $page = max((int) $request->input('page', 1), 1);
        $perPage = min(max((int) $request->input('per_page', 10), 1), 100);

        $productsQuery = Product::where('status', 1)->where('is_published', 1);

        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $productsQuery->where('category_id', $category->id);
            }
        }

        $relevance = null;
        if (!empty($query)) {
            $productsQuery->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhere('short_description', 'LIKE', "%{$query}%")
                    ->orWhere('slug', 'LIKE', "%{$query}%");
            });
            $relevance = [
                'CASE WHEN name = ? THEN 1 WHEN name LIKE ? THEN 2 WHEN name LIKE ? THEN 3 ELSE 4 END',
                [$query, $query . '%', '%' . $query . '%'],
            ];
        }

        $this->applyListingSort($productsQuery, $sort, $relevance);

        [$items, $pagination] = $this->paginateListing($productsQuery, $page, $perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'products'   => $items,
                'pagination' => $pagination,
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
                'reviews.user',
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

            $relatedModels = collect();
            if ($product->category_id) {
                $relatedModels = Product::select(['id', 'name', 'slug', 'thumbnail', 'num_of_sale', 'category_id', 'brand_id'])
                    ->with([
                        'price',
                        'inventory',
                        'category',
                        'brand',
                        'reviews' => fn($q) => $q->select('id', 'product_id', 'rating'),
                    ])
                    ->withExists('variants')
                    ->where('is_published', 1)
                    ->where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->limit(10)
                    ->get();
            }

            $photoIds = $product->photos ? json_decode($product->photos, true) : null;
            $photoIds = is_array($photoIds) ? $photoIds : [];

            // One query for every image on the page instead of one query per image.
            preload_uploaded_assets(array_merge(
                [$product->thumbnail, $product->category->image ?? null, $product->brand->logo ?? null],
                $photoIds,
                $product->variants->pluck('image')->all(),
                $relatedModels->pluck('thumbnail')->all()
            ));

            $galleryImages = [];
            foreach ($photoIds as $id) {
                if ($id == $product->thumbnail) {
                    continue;
                }
                $galleryImages[] = uploaded_asset($id);
            }

            $variants = $product->variants->map(function ($variant) use ($discount_amount) {
                $attributeValue = json_decode($variant->attribute_value, true) ?? [];
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

            $relatedProducts = $relatedModels->map(function ($rel) {
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
                    'reviews'         => $rel->reviews->count(),
                    'image'           => $rel->thumbnail ? uploaded_asset($rel->thumbnail) : null,
                    'sold'            => $rel->num_of_sale ?? 0,
                    'has_variants'    => $rel->variants_exists,
                    'in_stock'        => ($rel->inventory->stock ?? 0) > 0,
                    'category'        => $rel->category->category_name ?? null ,
                    'brand'           => $rel->brand?->name,
                ];
            })->values();


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
     * Price, rating and brand filters shared by the product listing endpoints.
     */
    private function applyListingFilters($query, Request $request)
    {
        $minPrice = $request->filled('min_price') ? (float) $request->get('min_price') : null;
        $maxPrice = $request->filled('max_price') ? (float) $request->get('max_price') : null;
        $rating = $request->filled('rating') ? (float) $request->get('rating') : null;
        $brands = $request->filled('brands')
            ? array_values(array_unique(array_filter(array_map(fn($value) => trim($value), explode(',', $request->get('brands'))))))
            : [];

        if ($minPrice !== null) {
            $query->whereRaw(self::LISTING_PRICE_SQL . ' >= ?', [$minPrice]);
        }

        if ($maxPrice !== null) {
            $query->whereRaw(self::LISTING_PRICE_SQL . ' <= ?', [$maxPrice]);
        }

        if ($rating !== null) {
            $query->whereRaw(self::LISTING_RATING_SQL . ' >= ?', [$rating]);
        }

        if ($brands !== []) {
            // Brand names are matched case-insensitively in PHP, as before.
            $brandNames = array_map('strtolower', $brands);
            $brandIds = Brand::get(['id', 'name'])
                ->filter(fn($brand) => in_array(strtolower((string) $brand->name), $brandNames, true))
                ->pluck('id')
                ->all();

            $query->whereIn('brand_id', $brandIds);
        }
    }

    /**
     * Order for the listing "sort" param. Products with the same price keep their
     * previous order: the tie-breaker (search relevance) first, then id.
     */
    private function applyListingSort($query, $sort, ?array $tieBreaker = null)
    {
        if ($sort === 'price-low' || $sort === 'price-high') {
            $query->orderByRaw(self::LISTING_PRICE_SQL . ($sort === 'price-low' ? ' ASC' : ' DESC'));
            if ($tieBreaker) {
                $query->orderByRaw($tieBreaker[0], $tieBreaker[1]);
            }
            $query->orderBy('products.id');
        } elseif ($sort === 'oldest') {
            $query->orderBy('products.id');
        } else {
            // 'newest', 'select' and unknown values
            $query->orderByDesc('products.id');
        }
    }

    /**
     * Count the matches, load only the requested page and format it for the listing response.
     * A page past the end returns the last page.
     */
    private function paginateListing($query, int $page, int $perPage)
    {
        $total = $query->toBase()->getCountForPagination();
        $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 0;
        $currentPage = min($page, $totalPages ?: 1);

        $products = $query
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
            ->with([
                'brand' => fn($q) => $q->select('id', 'name'),
                'price' => fn($q) => $q->select('product_id', 'regular_price', 'sale_price', 'discount', 'discount_type'),
                'inventory' => fn($q) => $q->select('product_id', 'stock'),
                'reviews' => fn($q) => $q->select('id', 'product_id', 'rating')->where('status', 1),
                'variants' => fn($q) => $q->select('id', 'product_id', 'price'),
                'campaigns',
            ])
            ->forPage($currentPage, $perPage)
            ->get();

        preload_uploaded_assets($products->pluck('thumbnail'));

        $items = ProductResource::collection($products)->resolve();

        // Variant products also return their min/max variant price.
        foreach ($products as $index => $product) {
            if ($product->variants->isNotEmpty()) {
                $prices = $product->variants->pluck('price')->map(fn($p) => (float) $p);
                $items[$index]['price_range'] = [
                    'min' => $prices->min(),
                    'max' => $prices->max(),
                ];
            }
        }

        return [$items, [
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'has_more' => $currentPage < $totalPages,
        ]];
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

        $query = Product::query()->where('category_id', $category->id);
        $this->applyListingFilters($query, $request);
        $this->applyListingSort($query, $request->get('sort', 'select'));

        [$items, $pagination] = $this->paginateListing($query, $page, $perPage);

        // Get subcategories for this category
        $subCategories = \App\Models\Admin\SubCategory::where('category_id', $category->id)
            ->select(['id', 'name', 'slug', 'image'])
            ->get()
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $items,
                'pagination' => $pagination,
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
