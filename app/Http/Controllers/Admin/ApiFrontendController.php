<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageRequest;
use App\Http\Resources\BlogResource;
use App\Http\Resources\SingleBlogResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\CounterResource;
use App\Http\Resources\DiscoverResource;
use App\Http\Resources\VideoResource;
use App\Http\Resources\DropshiperReviewResource;
use App\Http\Resources\FaqResource;
use App\Http\Resources\SettingResource;
use App\Models\Admin\Attribute;
use App\Models\Admin\Blog;
use App\Models\Admin\Color;
use App\Models\Admin\Counter;
use App\Models\Admin\Discover;
use App\Models\Admin\DropshiperReview;
use App\Models\Admin\Faq;
use App\Models\Admin\Product;
use App\Models\Admin\Slider;
use App\Models\Admin\Team;
use App\Models\Admin\Video;
use App\Models\BusinessSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psy\Util\Json;

class ApiFrontendController extends Controller
{
    public function test()
    {
        $users = User::latest()->get();
        return successResponse(
            'Users fetched successfully',
            $users,
            200
        );
    }

    public function index()
    {
        $slider = BusinessSetting::where('type', 'home_slider')->first();
        $sliderIds = json_decode($slider->value ?? '[]', true);
        $sliders = Slider::whereIn('id', array_unique($sliderIds))
            ->latest()
            ->get();

        $counter = Counter::firstOrFail();
        $discovers = Discover::latest('id')->get();
        $video = Video::firstOrFail();
        $reviews = DropshiperReview::latest('id')->where('status', DropshiperReview::ACTIVE)->get();


        $blog = BusinessSetting::where('type', 'home_blog')->first();
        $blogIds = json_decode($blog->value, true) ?? [];

        $blogs = Blog::whereIn('id', array_unique($blogIds))
            ->latest('id')
            ->get();

        $faqs = Faq::oldest('position')->get();

        // return response()->json($faqs);

        return successResponse(
            'Home data fetched successfully',
            [
                'sliders'   => SliderResource::collection($sliders),
                'counter'   => new CounterResource($counter),
                'discovers' => DiscoverResource::collection($discovers),
                'video'     => new VideoResource($video),
                'reviews'   => DropshiperReviewResource::collection($reviews),
                'blogs'     => BlogResource::collection($blogs),
                'faqs'      => FaqResource::collection($faqs)
            ],
            200
        );

        return response()->json($sliders);
    }

    public function blog()
    {
        try {
            $blog = BusinessSetting::where('type', 'blog_blog')->first();
            $slider = BusinessSetting::where('type', 'blog_slider')->first();

            if (!$blog) {
                return errorResponse('Blog setting not found', [], 404);
            }

            $blogIds = json_decode($blog->value, true) ?? [];
            $sliderIds = json_decode($slider->value ?? '[]', true);

            $blogs = Blog::whereIn('id', array_unique($blogIds))
                ->latest('id')
                ->get();

            $sliders = Slider::whereIn('id', array_unique($sliderIds))
                ->latest()
                ->get();

            return successResponse(
                'Blog data fetched successfully',
                [
                    'sliders' => SliderResource::collection($sliders),
                    'blogs'   => BlogResource::collection($blogs),
                ],
                200
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function singleBlog($slug)
    {
        try {
            $blog = Blog::with([
                'user:id,name',
                'mainImage',
                'metaImage'
            ])
                ->where('slug', $slug)
                ->first();

            if (!$blog) {
                return errorResponse('Blog not found', [], 404);
            }

            $blogs = Blog::with(['user:id,name', 'thumbnailImage'])
                ->latest()
                ->take(9)
                ->get();

            return successResponse(
                'Blog data fetched successfully',
                [
                    'blog'         => new SingleBlogResource($blog),
                    'latest_blogs' => BlogResource::collection($blogs),
                ],
                200
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function  aboutUs()
    {
        try {
            $slider = BusinessSetting::where('type', 'about-us_slider')->first();
            $sliderIds = json_decode($slider->value ?? '[]', true);
            $sliders = Slider::whereIn('id', array_unique($sliderIds))
                ->latest()
                ->get();
            // $teams = Team::oldest('position')->get();

            return successResponse(
                'About data fetched successfully',
                [
                    'sliders' => SliderResource::collection($sliders)
                ],
                200
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function  contactUs()
    {
        try {
            $slider = BusinessSetting::where('type', 'contact-us_slider')->first();
            $sliderIds = json_decode($slider->value ?? '[]', true);
            $sliders = Slider::whereIn('id', array_unique($sliderIds))
                ->latest()
                ->get();

            return successResponse(
                'Contact data fetched successfully',
                [
                    'sliders' => SliderResource::collection($sliders)
                ],
                200
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function integration()
    {
        try {
            $slider = BusinessSetting::where('type', 'integration_slider')->first();
            $sliderIds = json_decode($slider->value ?? '[]', true);
            $sliders = Slider::whereIn('id', array_unique($sliderIds))->latest()->get();

            $discover = Discover::firstOrFail();

            return successResponse(
                'Integration data fetched successfully',
                [
                    'sliders'   => SliderResource::collection($sliders),
                    'discover'  => $discover,
                ],
                200
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function contactStore(ContactMessageRequest $request)
    {
        try {
            $contact = \App\Models\Admin\ContactMessage::create([
                'full_name' => $request->full_name,
                'email'     => $request->email,
                'subject'   => $request->subject,
                'message'   => $request->message,
            ]);

            return successResponse(
                'Message sent successfully',
                $contact,
                201
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Get product details by slug.
     *
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function productShow($slug)
    {
        try {
            $product = Product::with([
                'inventory',
                'price',
                'variants.attributeRel',
                'category',
                'brand',
                'reviews' // assuming you have a reviews relationship
            ])
                ->where('slug', $slug)
                ->where('is_published', 1)
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                    'data'    => null
                ], 404);
            }

            // ---------- Gallery images ----------
            $galleryImages = [];
            if ($product->photos) {
                $photoIds = json_decode($product->photos, true);
                if (is_array($photoIds)) {
                    foreach ($photoIds as $id) {
                        if ($id == $product->thumbnail) continue;
                        $galleryImages[] = uploaded_asset($id);
                    }
                }
            }

            // ---------- Color mapping ----------
            $colorIds = $product->variants->pluck('color')->unique()->filter();
            $colorsMap = Color::whereIn('id', $colorIds)->get()->keyBy('id');

            // ---------- Build attributes ----------
            // 1) Group variants by attribute (e.g., Age) – exclude color from variant details
            $groupedAttributes = $product->variants
                ->groupBy('attribute')
                ->map(function ($variants, $attrId) {
                    $first = $variants->first();
                    $attribute = $first->attributeRel;

                    return [
                        'id'     => (int) $attrId,
                        'name'   => $attribute ? $attribute->name : null,
                        'values' => $variants->map(function ($variant) {
                            return [
                                'value'   => $variant->attribute_value,
                                'variant' => [
                                    'id'    => $variant->id,
                                    'sku'   => $variant->sku ?? 'N/A',
                                    'price' => $variant->price,
                                    'stock' => $variant->quantity ?? 0,
                                    'image' => $variant->image ? uploaded_asset($variant->image) : null,
                                ]
                            ];
                        })->values()
                    ];
                })->values();

            // 2) Add Color as a separate attribute if colors exist
            $colorsList = $colorsMap->map(function ($color) {
                return [
                    'id'   => $color->id,
                    'name' => $color->name,
                ];
            })->values();

            if ($colorsList->isNotEmpty()) {
                // Build color attribute values – take the first variant for each color
                $colorValues = $colorsList->map(function ($colorData) use ($product) {
                    $colorId = $colorData['id'];
                    $variant = $product->variants->firstWhere('color', $colorId);
                    return [
                        'value'   => $colorData['name'],
                        'variant' => $variant ? [
                            'id'    => $variant->id,
                            'sku'   => $variant->sku ?? 'N/A',
                            'price' => $variant->price,
                            'stock' => $variant->quantity ?? 0,
                            'image' => $variant->image ? uploaded_asset($variant->image) : null,
                        ] : null
                    ];
                })->filter(function ($item) {
                    return !is_null($item['variant']);
                })->values();

                if ($colorValues->isNotEmpty()) {
                    $colorAttribute = [
                        'id'     => 1, // or some constant ID
                        'name'   => 'Color',
                        'values' => $colorValues
                    ];
                    // Insert color attribute before or after – keeping order doesn't matter
                    $groupedAttributes->push($colorAttribute);
                }
            }

            // ---------- Build review data ----------
            $reviews = $product->reviews; // assume relation exists
            $reviewData = [
                'rating'        => $product->rating ?? 0,
                'reviews_count' => $product->reviews_count ?? 0,
                'items'         => $reviews ? $reviews->map(function ($review) {
                    return [
                        'id'           => $review->id,
                        'rating'       => $review->rating,
                        'reviews_count' => $review->reviews_count ?? 0, // if needed
                        'comment'      => $review->comment,
                        'user'         => [
                            'id'     => $review->user_id,
                            'name'   => $review->user->name ?? null,
                            'avatar' => $review->user->avatar ?? null,
                        ],
                        'created_at'   => $review->created_at->toDateTimeString(),
                    ];
                })->values() : []
            ];

            // ---------- Build final product array ----------
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
                    'name'  => $product->category->name,
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
                    'unit'         => $product->inventory->unit ?? 'piece',
                    'sku'          => $product->inventory->sku ?? 'N/A',
                    'stock'        => $product->inventory->stock ?? 0,
                    'stock_status' => ($product->inventory->stock ?? 0) > 0 ? 'in_stock' : 'out_of_stock',
                    'total_sold'   => $product->inventory->total_sold ?? 0,
                ],

                'attributes'      => $groupedAttributes,
                'has_variants'    => $product->variants->count() > 0, // boolean
                'review'          => $reviewData,
                'created_at'      => $product->created_at->toDateTimeString(),
                'updated_at'      => $product->updated_at->toDateTimeString(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully',
                'product'    => $formattedProduct   // wrapped in "data" as before
            ]);
        } catch (\Exception $e) {
            Log::error('Product API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch product: ' . $e->getMessage(),
                'product'    => null,
            ], 500);
        }
    }




    /**
     * Helper: Get current price
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
     * Helper: Calculate discount percentage
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

    public function settings()
    {
        $settings  = SettingResource::collection(BusinessSetting::get());
        return successResponse(
            'Settings fetched successfully',
            [
                'settings' => $settings
            ],
            200
        );
    }
}
