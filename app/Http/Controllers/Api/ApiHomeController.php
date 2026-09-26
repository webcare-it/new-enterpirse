<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\BrandResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\BlogResource;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\Brand;
use App\Models\Admin\Blog;
use App\Models\Search;
use App\Models\Admin\Campaign;
use App\Models\Admin\Newsletter;
use App\Models\Admin\Slider;
use App\Models\BusinessSetting;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiHomeController extends Controller
{
    public function index()
    {
        // Read settings
        $sliderIds = json_decode(get_setting('home_sliders'), true) ?? [];
        $campaignIds = json_decode(get_setting('home_campaigns'), true) ?? [];
        $newArrivalIds = json_decode(get_setting('h_new_a_products'), true) ?? [];
        $bestSellingIds = json_decode(get_setting('h_best_s_products'), true) ?? [];
        $todaysDealIds = json_decode(get_setting('h_todays_d_products'), true) ?? [];
        $featuredIds = json_decode(get_setting('h_featured_products'), true) ?? [];
        $categoryIds = json_decode(get_setting('home_categories'), true) ?? [];
        $categoryProductLimit = (int) (get_setting('h_category_p_limit') ?? 10);
        $blogIds = json_decode(get_setting('home_blogs'), true) ?? [];

        // ---- BASE QUERY (relations are eager loaded once for all sections below) ----
        $baseQuery = Product::query()
            ->where('is_published', true)
            ->latest()
            ->select([
                'id',
                'name',
                'slug',
                'thumbnail',
                'brand_id',
                'category_id',
                'num_of_sale',
                'status',
                'is_published',
                'is_variant',
                'created_at',
                'updated_at',
            ]);

        // ---- Helper to add price_range (variant products only) to a product resource array ----
        $addExtraFields = function (array $items, $models) {
            // $items and $models are in the same order
            foreach ($models as $index => $model) {
                if ($model->variants->isNotEmpty()) {
                    // Add price range from variant retail prices
                    $prices = $model->variants->pluck('price')->map(fn($p) => (float) $p);
                    $items[$index]['price_range'] = [
                        'min' => $prices->min(),
                        'max' => $prices->max(),
                    ];
                }
            }
            return $items;
        };

        // ---- Sliders ----
        $sliderModels = !empty($sliderIds)
            ? Slider::whereIn('id', $sliderIds)->latest('id')->get()
            : Slider::latest('id')->get();

        // ---- Campaigns ----
        $campaignModels = !empty($campaignIds)
            ? Campaign::whereIn('id', $campaignIds)->latest()->get()
            : collect();

        // ---- Product sections ----
        $todaysDealModels = !empty($todaysDealIds)
            ? (clone $baseQuery)->whereIn('id', $todaysDealIds)->get()
            : (clone $baseQuery)->where('todays_deal', '>', 0)->limit(10)->get();

        $bestSellingModels = !empty($bestSellingIds)
            ? (clone $baseQuery)->whereIn('id', $bestSellingIds)->get()
            : (clone $baseQuery)->where('best_selling', true)->limit(10)->get();

        $featuredModels = !empty($featuredIds)
            ? (clone $baseQuery)->whereIn('id', $featuredIds)->get()
            : (clone $baseQuery)->where('is_featured', true)->limit(10)->get();

        $newArrivalModels = !empty($newArrivalIds)
            ? (clone $baseQuery)->whereIn('id', $newArrivalIds)->get()
            : (clone $baseQuery)->where('is_new_arrival', true)->orderBy('created_at', 'desc')->limit(10)->get();

        // ---- Categories with products ----
        $targetCategoryIds = !empty($categoryIds) ? $categoryIds : Category::pluck('id')->toArray();
        $categoriesById = Category::whereIn('id', $targetCategoryIds)->get()->keyBy('id');

        $categorySections = [];
        foreach ($targetCategoryIds as $categoryId) {
            $category = $categoriesById->get($categoryId) ?? Category::find($categoryId);
            if (! $category) continue;

            $categorySections[] = [
                'category' => $category,
                'products' => (clone $baseQuery)
                    ->where('category_id', $categoryId)
                    ->limit($categoryProductLimit)
                    ->get(),
            ];
        }

        // ---- Eager load product relations once for every section ----
        $allProducts = new EloquentCollection(array_merge(
            $todaysDealModels->all(),
            $bestSellingModels->all(),
            $featuredModels->all(),
            $newArrivalModels->all(),
            ...array_map(fn($section) => $section['products']->all(), $categorySections)
        ));

        $allProducts->load([
            'brand' => fn($q) => $q->select('id', 'name'),
            'category' => fn($q) => $q->select('id', 'category_name', 'slug'),
            'price' => fn($q) => $q->select('product_id', 'regular_price', 'sale_price', 'discount', 'discount_type'),
            'inventory' => fn($q) => $q->select('product_id', 'stock'),
            'reviews' => fn($q) => $q->select('id', 'product_id', 'rating')->where('status', 1),
            'variants' => fn($q) => $q->select('id', 'product_id', 'price'),
            'campaigns',
        ]);

        // ---- One query for every image on the page ----
        preload_uploaded_assets(array_merge(
            $allProducts->pluck('thumbnail')->all(),
            $sliderModels->pluck('photos')->all(),
            $campaignModels->pluck('image')->all(),
            array_map(fn($section) => $section['category']->hero_image, $categorySections)
        ));

        $sliders = SliderResource::collection($sliderModels);

        $campaigns = [];
        if (!empty($campaignIds)) {
            $campaigns = $campaignModels->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'slug' => $campaign->slug,
                    'start_date' => $campaign->start_date,
                    'end_date' => $campaign->end_date,
                    'discount_amount' => $campaign->discount_amount,
                    'discount_type' => $campaign->discount_type,
                    'image' => $campaign->image ? uploaded_asset($campaign->image) : null,
                ];
            });
        }

        $todays_deal = $addExtraFields(
            ProductResource::collection($todaysDealModels)->resolve(),
            $todaysDealModels
        );
        $best_sellers = $addExtraFields(
            ProductResource::collection($bestSellingModels)->resolve(),
            $bestSellingModels
        );
        $featured = $addExtraFields(
            ProductResource::collection($featuredModels)->resolve(),
            $featuredModels
        );
        $new_arrivals = $addExtraFields(
            ProductResource::collection($newArrivalModels)->resolve(),
            $newArrivalModels
        );

        $categories = [];
        foreach ($categorySections as $section) {
            $category = $section['category'];

            $categories[] = [
                'id' => (int) $category->id,
                'name' => $category->category_name,
                'slug' => $category->slug,
                'image' => $category->category_image,
                'hero_image' => uploaded_asset($category->hero_image),
                'products' => $addExtraFields(
                    ProductResource::collection($section['products'])->resolve(),
                    $section['products']
                ),
            ];
        }

                // Blogs
        $blogs = [];
        if (!empty($blogIds)) {
            $blogs = BlogResource::collection(
                Blog::whereIn('id', $blogIds)->latest()->get()
            )->resolve();
        } else {
            $blogs = BlogResource::collection(
                Blog::latest()->take(4)->get()
            )->resolve();
        }

        // ---- Final response ----
        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully',
            'data' => [
                'sliders' => $sliders,
                'blogs' => $blogs,
                'todays_deal' => $todays_deal,
                'best_selling' => $best_sellers,
                'new_arrivals' => $new_arrivals,
                'featured' => $featured,
                'categories' => $categories,
                'campaigns' => $campaigns,
            ],
        ], 200);
    }

    /**
     * Get products grouped by requested types (via query parameters).
     * Only types with value = 1 are returned.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function collections(Request $request)
    {
        $typeMap = [
            'best_selling'   => 'best_selling',
            'is_featured'    => 'is_featured',
            'todays-deal'    => 'todays_deal',
            'is_new_arrival' => 'is_new_arrival',
        ];

        $query = Product::with([
            'price' => fn($q) => $q->select('product_id', 'regular_price', 'sale_price', 'discount', 'discount_type'),
            'inventory' => fn($q) => $q->select('product_id', 'stock'),
            'variants' => fn($q) => $q->select('id', 'product_id', 'price'),
            'reviews' => fn($q) => $q->select('id', 'product_id', 'rating')->where('status', 1),
            'campaigns',
        ])
            ->where('status', 1)
            ->where('is_published', 1);

        $hasFilter = false;

        foreach ($typeMap as $queryKey => $dbColumn) {
            if ($request->has($queryKey) && $request->input($queryKey) == '1') {
                $hasFilter = true;
                if ($dbColumn === 'todays_deal') {
                    $query->orWhere('todays_deal', '>', 0);
                } else {
                    $query->orWhere($dbColumn, 1);
                }
            }
        }

        if (!$hasFilter) {
            return response()->json([
                'success' => true,
                'status'  => 200,
                'message' => 'No product types requested (or all values are 0)',
                'data'    => [
                    'products'   => [],
                    'pagination' => [
                        'current_page' => 1,
                        'per_page'     => 15,
                        'total'        => 0,
                        'total_pages'  => 0,
                        'has_more'     => false,
                    ],
                ],
            ]);
        }

        $perPage = $request->input('per_page', 15);
        $products = $query->paginate($perPage);

        // ---- Add price_range (variant products only) ----
        $models = $products->getCollection();
        preload_uploaded_assets($models->pluck('thumbnail'));
        $items = ProductResource::collection($products)->resolve();

        foreach ($models as $index => $model) {
            if ($model->variants->isNotEmpty()) {
                // Price range from variant retail prices
                $prices = $model->variants->pluck('price')->map(fn($p) => (float) $p);
                $items[$index]['price_range'] = [
                    'min' => $prices->min(),
                    'max' => $prices->max(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved',
            'data'    => [
                'products'   => $items,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'per_page'     => $products->perPage(),
                    'total'        => $products->total(),
                    'total_pages'  => $products->lastPage(),
                    'has_more'     => $products->currentPage() < $products->lastPage(),
                ],
            ],
        ]);
    }

    public function newsletter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $email = $request->email;

        $exists = Newsletter::where('email', $email)->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already subscribed.'
            ], 404);
        }

        try {
            $subscription = Newsletter::create([
                'email' => $email,
                'subscribed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to the newsletter.',
                'data'    => $subscription
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe. Please try again later.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function searchSuggestions(Request $request)
    {
        $query = trim((string) $request->input('query', ''));

        if (empty($query)) {
            return response()->json([
                'success' => true,
                'message' => 'No search query provided.',
                'data'    => [],
            ]);
        }

        $suggestions = Search::where('query', 'LIKE', "%{$query}%")
            ->orderBy('count', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->pluck('query')
            ->unique()
            ->values()
            ->toArray();

        return response()->json([
            'success' => true,
            'message' => 'Search suggestions.',
            'data'    => $suggestions,
        ]);
    }
}
