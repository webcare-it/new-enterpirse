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

        // ---- BASE QUERY (with eager loads) ----
        $baseQuery = Product::query()
            ->where('is_published', true)
            ->latest()
            ->with([
                'brand' => fn($q) => $q->select('id', 'name'),
                'category' => fn($q) => $q->select('id', 'category_name', 'slug'),
                'price' => fn($q) => $q->select(
                    'product_id',
                    'regular_price',
                    'sale_price',
                    'discount',
                    'discount_type',
                    'wholesale_price'   // make sure this column exists
                ),
                'inventory' => fn($q) => $q->select('product_id', 'stock'),
                'reviews' => fn($q) => $q->where('status', 1),
                'variants' => fn($q) => $q->select('id', 'product_id', 'price', 'wholesale_price'),
            ])
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

        // ---- Helper to add extra fields to a product resource array ----
        $addExtraFields = function (array $items, $models) {
            // $models should be a collection of Product models in the same order
            foreach ($items as $index => &$item) {
                $model = $models->firstWhere('id', $item['id']);
                if (! $model) {
                    unset($item['price_range']);
                    continue;
                }

                if ($model->variants->isNotEmpty()) {

                    // Add price range from variant retail prices
                    $prices = $model->variants->pluck('price')->map(fn($p) => (float) $p);
                    $item['price_range'] = [
                        'min' => $prices->min(),
                        'max' => $prices->max(),
                    ];
                } else {
                    // Simple product → fallback to parent wholesale_price
                    unset($item['price_range']); // or set to null
                }
            }
            return $items;
        };

        // ---- Sliders ----
        $sliders = SliderResource::collection(
            !empty($sliderIds)
                ? Slider::whereIn('id', $sliderIds)->latest('id')->get()
                : Slider::latest('id')->get()
        );

        // ---- Campaigns ----
        $campaigns = [];
        if (!empty($campaignIds)) {
            $campaigns = Campaign::whereIn('id', $campaignIds)->latest()->get()->map(function ($campaign) {
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

        // ---- Today's deals ----
        $todaysDealModels = !empty($todaysDealIds)
            ? (clone $baseQuery)->whereIn('id', $todaysDealIds)->get()
            : (clone $baseQuery)->where('todays_deal', '>', 0)->limit(10)->get();
        $todays_deal = $addExtraFields(
            ProductResource::collection($todaysDealModels)->resolve(),
            $todaysDealModels
        );

        // ---- Best selling ----
        $bestSellingModels = !empty($bestSellingIds)
            ? (clone $baseQuery)->whereIn('id', $bestSellingIds)->get()
            : (clone $baseQuery)->where('best_selling', true)->limit(10)->get();
        $best_sellers = $addExtraFields(
            ProductResource::collection($bestSellingModels)->resolve(),
            $bestSellingModels
        );

        // ---- Featured ----
        $featuredModels = !empty($featuredIds)
            ? (clone $baseQuery)->whereIn('id', $featuredIds)->get()
            : (clone $baseQuery)->where('is_featured', true)->limit(10)->get();
        $featured = $addExtraFields(
            ProductResource::collection($featuredModels)->resolve(),
            $featuredModels
        );

        // ---- New arrivals ----
        $newArrivalModels = !empty($newArrivalIds)
            ? (clone $baseQuery)->whereIn('id', $newArrivalIds)->get()
            : (clone $baseQuery)->where('is_new_arrival', true)->orderBy('created_at', 'desc')->limit(10)->get();
        $new_arrivals = $addExtraFields(
            ProductResource::collection($newArrivalModels)->resolve(),
            $newArrivalModels
        );

        // ---- Categories with products ----
        $categories = [];
        $targetCategoryIds = !empty($categoryIds) ? $categoryIds : Category::pluck('id')->toArray();

        foreach ($targetCategoryIds as $categoryId) {
            $category = Category::find($categoryId);
            if (! $category) continue;

            $categoryProductModels = (clone $baseQuery)
                ->where('category_id', $categoryId)
                ->limit($categoryProductLimit)
                ->get();

            $categoryProducts = $addExtraFields(
                ProductResource::collection($categoryProductModels)->resolve(),
                $categoryProductModels
            );

            $categories[] = [
                'id' => (int) $category->id,
                'name' => $category->category_name,
                'slug' => $category->slug,
                'image' => $category->category_image,
                'hero_image' => uploaded_asset($category->hero_image),
                'products' => $categoryProducts,
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
                Blog::latest()->take(3)->get()
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
            'price' => fn($q) => $q->select('product_id', 'regular_price', 'sale_price', 'discount', 'discount_type', 'wholesale_price'),
            'inventory' => fn($q) => $q->select('product_id', 'stock'),
            'variants' => fn($q) => $q->select('id', 'product_id', 'price', 'wholesale_price'),
            'reviews' => fn($q) => $q->where('status', 1),
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

        // ---- Add extra fields (wholesale_price & price_range) ----
        $items = ProductResource::collection($products)->resolve();
        $models = $products->getCollection();

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
                $item['wholesale_price'] = $model->price ? (float) $model->price->wholesale_price : null;
                unset($item['price_range']);
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
            ], 409);
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
