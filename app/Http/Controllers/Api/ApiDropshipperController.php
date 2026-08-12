<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\ProductDropshipprResource;
use App\Models\Admin\Brand;
use App\Models\Admin\Category;
use App\Models\Admin\Color;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVarient;
use App\Models\Dropshipper;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiDropshipperController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:dropshippers,email',
            'user_name'      => 'required|string|max:255|unique:dropshippers,user_name',
            'domain_name'    => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'password'       => 'required|string|min:8',
            'profile_image'  => 'nullable|string',
            'dropshipper_id' => 'nullable|integer',
            'package_id'     => 'nullable|integer',
            'total_deposit'  => 'nullable|numeric|min:0',
            'total_credit'   => 'nullable|numeric|min:0',
            'total_withdraw' => 'nullable|numeric|min:0',
            'app_key'        => 'nullable|string|max:255',
            'app_secret'     => 'nullable|string|max:255',
            'is_approved'    => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Auto‑generate app_key and app_secret if not provided
        if (empty($validated['app_key'])) {
            $validated['app_key'] = Str::random(32);
        }
        if (empty($validated['app_secret'])) {
            $validated['app_secret'] = $this->generateSecureSecret(48);
        }

        // Handle base64 image - store directly in public directory
        if (!empty($validated['profile_image'])) {
            $imageData = $validated['profile_image'];
            if ($this->isBase64($imageData)) {
                // Extract mime type and extension
                preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches);
                $ext = $matches[1] ?? 'png';
                $ext = ($ext === 'jpeg') ? 'jpg' : $ext;

                // Generate unique filename
                $imageName = Str::uuid() . '.' . $ext;
                $relativePath = 'uploads/dropshippers/' . $imageName;
                $fullPath = public_path($relativePath);

                // Ensure directory exists
                File::ensureDirectoryExists(public_path('uploads/dropshippers'));

                // Decode and save
                $decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
                if ($decoded !== false) {
                    file_put_contents($fullPath, $decoded);
                    $validated['image'] = $relativePath; // store relative path
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid base64 image data'
                    ], 422);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid base64 image format'
                ], 422);
            }
        }

        // Remove profile_image from validated data
        unset($validated['profile_image']);

        // Set is_approved default if not provided
        $validated['is_approved'] = $validated['is_approved'] ?? false;

        // Create the dropshipper
        $dropshipper = Dropshipper::create($validated);

        // Exclude password from response
        $dropshipper->makeHidden(['password']);

        return response()->json([
            'success' => true,
            'message' => 'Dropshipper created successfully',
            'data'    => $dropshipper
        ], 201);
    }

    /**
     * Generate a cryptographically secure secret with special characters.
     *
     * @param int $length
     * @return string
     */
    private function generateSecureSecret($length = 48)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{};:,.<>?';
        return substr(str_shuffle(str_repeat($chars, ceil($length / strlen($chars)))), 0, $length);
    }

    /**
     * Check if a string is a valid base64‑encoded image.
     *
     * @param string $string
     * @return bool
     */
    private function isBase64($string)
    {
        if (!is_string($string)) {
            return false;
        }
        return preg_match('/^data:image\/(jpeg|png|jpg|gif|webp);base64,/', $string) === 1;
    }

    public function products(Request $request)
    {
        // ----- Authentication (unchanged) ----- price_range
        $apiKey     = $request->header('api_key');
        $apiSecret  = $request->header('api_secret');
        $apiUsername = $request->header('username');

        if (!$apiKey || !$apiSecret || !$apiUsername) {
            return response()->json([
                'success' => false,
                'message' => 'Missing authentication headers: api_key, api_secret, and username are required.',
            ], 401);
        }

        $dropshipper = Dropshipper::where('app_key', $apiKey)
            ->where('app_secret', $apiSecret)
            ->where('user_name', $apiUsername)
            ->first();

        if (!$dropshipper) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API credentials.',
            ], 401);
        }

        if ($dropshipper->is_approved != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not approved. Please contact support.',
            ], 403);
        }

        // ----- Build base query with necessary relations -----
        $baseQuery = Product::query()
            ->where('is_published', true)
            ->latest()
            ->with([
                'brand'    => fn($q) => $q->select('id', 'name'),
                'category' => fn($q) => $q->select('id', 'category_name', 'slug'),
                'price'    => fn($q) => $q->select('product_id', 'regular_price', 'sale_price', 'wholesale_price'),
                'inventory' => fn($q) => $q->select('product_id', 'stock'),
                'reviews'  => fn($q) => $q->where('status', 1),
                // 👇 Load both wholesale_price AND price for variants
                'variants' => fn($q) => $q->select('id', 'product_id', 'wholesale_price', 'price')
            ])
            ->select([
                'id',
                'name',
                'slug',
                'short_description',
                'thumbnail',
                'brand_id',
                'category_id',
                'num_of_sale',
                'status',
                'is_published',
                'is_variant',
                'created_at',
                'updated_at'
            ]);

        // ----- Apply filters (unchanged) -----
        $filters = [];

        if ($request->has('in_stock') && $request->in_stock !== '') {
            $inStock = filter_var($request->in_stock, FILTER_VALIDATE_BOOLEAN);
            if ($inStock) {
                $baseQuery->whereHas('inventory', function ($q) {
                    $q->where('stock', '>', 0);
                });
            } else {
                $baseQuery->whereHas('inventory', function ($q) {
                    $q->where('stock', '<=', 0);
                });
            }
            $filters['in_stock'] = $inStock;
        }

        if ($request->has('has_variant') && $request->has_variant !== '') {
            $hasVariant = filter_var($request->has_variant, FILTER_VALIDATE_BOOLEAN);
            $baseQuery->where('is_variant', $hasVariant ? 1 : 0);
            $filters['has_variant'] = $hasVariant;
        }

        // ----- Pagination -----
        $perPage = (int) $request->input('per_page', 150);
        $perPage = max(1, min(200, $perPage));
        $page = (int) $request->input('page', 1);

        $paginator = $baseQuery->paginate($perPage, ['*'], 'page', $page);

        // ----- Transform products using the existing ProductDropshipprResource -----
        $products = ProductDropshipprResource::collection($paginator)->resolve();

        // ----- Override wholesale_price and add price_range (if variants exist) -----
        foreach ($products as &$product) {
            $model = $paginator->getCollection()->firstWhere('id', $product['id']);
            if ($model) {
                if ($model->variants->isNotEmpty()) {
                    // Variants exist → max wholesale
                    $product['wholesale_price'] = (float) $model->variants->max('wholesale_price');

                    // Add price_range using variant retail prices
                    $prices = $model->variants->pluck('price')->map(fn($p) => (float) $p);
                    $product['price_range'] = [
                        'min' => $prices->min(),
                        'max' => $prices->max(),
                    ];
                } else {
                    // No variants → fallback to parent wholesale, and unset price_range
                    $product['wholesale_price'] = $model->price ? (float) $model->price->wholesale_price : null;
                    unset($product['price_range']); // or set to null
                }
            } else {
                $product['wholesale_price'] = null;
                unset($product['price_range']);
            }
        }

        // ----- Build links (unchanged) -----
        $baseUrl = $request->url();
        $queryParams = $request->query();
        unset($queryParams['page']);

        $selfParams = $queryParams;
        $selfUrl = $baseUrl . '?' . http_build_query($selfParams);

        $nextUrl = null;
        if ($paginator->hasMorePages()) {
            $nextParams = array_merge($queryParams, [
                'page' => $paginator->currentPage() + 1,
                'per_page' => $perPage,
            ]);
            $nextUrl = $baseUrl . '?' . http_build_query($nextParams);
        }

        $prevUrl = null;
        if ($paginator->currentPage() > 1) {
            $prevParams = array_merge($queryParams, [
                'page' => $paginator->currentPage() - 1,
                'per_page' => $perPage,
            ]);
            $prevUrl = $baseUrl . '?' . http_build_query($prevParams);
        }

        $filters = array_merge([
            'page'     => $page,
            'per_page' => $perPage,
        ], $filters);

        // ----- Final response -----
        return response()->json([
            'success' => true,
            'message' => 'Products fetched successfully',
            'data' => [
                'products'   => $products,
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page'     => $paginator->perPage(),
                    'from'         => $paginator->firstItem(),
                    'to'           => $paginator->lastItem(),
                    'total'        => $paginator->total(),
                    'total_pages'  => $paginator->lastPage(),
                    'has_more'     => $paginator->hasMorePages(),
                ],
                'filters' => $filters,
                'links'   => [
                    'self'     => $selfUrl,
                    'next'     => $nextUrl,
                    'previous' => $prevUrl,
                ]
            ],
        ], 200);
    }


    public function productsFree(Request $request)
    {
        // ----- Build base query with necessary relations -----
        $baseQuery = Product::query()
            ->where('is_published', true)
            ->latest()
            ->with([
                'brand'    => fn($q) => $q->select('id', 'name'),
                'category' => fn($q) => $q->select('id', 'category_name', 'slug'),
                'price'    => fn($q) => $q->select('product_id', 'regular_price', 'sale_price', 'wholesale_price'),
                'inventory' => fn($q) => $q->select('product_id', 'stock'),
                'reviews'  => fn($q) => $q->where('status', 1),
                'variants' => fn($q) => $q->select('id', 'product_id', 'wholesale_price', 'price')
            ])
            ->select([
                'id',
                'name',
                'slug',
                'short_description',
                'thumbnail',
                'brand_id',
                'category_id',
                'num_of_sale',
                'status',
                'is_published',
                'is_variant',
                'created_at',
                'updated_at'
            ]);

        // ----- Apply filters (unchanged) -----
        $filters = [];

        if ($request->has('in_stock') && $request->in_stock !== '') {
            $inStock = filter_var($request->in_stock, FILTER_VALIDATE_BOOLEAN);
            if ($inStock) {
                $baseQuery->whereHas('inventory', function ($q) {
                    $q->where('stock', '>', 0);
                });
            } else {
                $baseQuery->whereHas('inventory', function ($q) {
                    $q->where('stock', '<=', 0);
                });
            }
            $filters['in_stock'] = $inStock;
        }

        if ($request->has('has_variant') && $request->has_variant !== '') {
            $hasVariant = filter_var($request->has_variant, FILTER_VALIDATE_BOOLEAN);
            $baseQuery->where('is_variant', $hasVariant ? 1 : 0);
            $filters['has_variant'] = $hasVariant;
        }

        // ----- Pagination -----
        $perPage = (int) $request->input('per_page', 150);
        $perPage = max(1, min(200, $perPage));
        $page = (int) $request->input('page', 1);

        $paginator = $baseQuery->paginate($perPage, ['*'], 'page', $page);


        $products = ProductDropshipprResource::collection($paginator)->resolve();

        foreach ($products as &$product) {
            $model = $paginator->getCollection()->firstWhere('id', $product['id']);
            if ($model) {
                if ($model->variants->isNotEmpty()) {
                    // Variants exist → max wholesale
                    $product['wholesale_price'] = (float) $model->variants->max('wholesale_price');

                    // Add price_range using variant retail prices
                    $prices = $model->variants->pluck('price')->map(fn($p) => (float) $p);
                    $product['price_range'] = [
                        'min' => $prices->min(),
                        'max' => $prices->max(),
                    ];
                } else {
                    $product['wholesale_price'] = $model->price ? (float) $model->price->wholesale_price : null;
                    unset($product['price_range']); // or set to null
                }
            } else {
                $product['wholesale_price'] = null;
                unset($product['price_range']);
            }
        }

        // ----- Build links (unchanged) -----
        $baseUrl = $request->url();
        $queryParams = $request->query();
        unset($queryParams['page']);

        $selfParams = $queryParams;
        $selfUrl = $baseUrl . '?' . http_build_query($selfParams);

        $nextUrl = null;
        if ($paginator->hasMorePages()) {
            $nextParams = array_merge($queryParams, [
                'page' => $paginator->currentPage() + 1,
                'per_page' => $perPage,
            ]);
            $nextUrl = $baseUrl . '?' . http_build_query($nextParams);
        }

        $prevUrl = null;
        if ($paginator->currentPage() > 1) {
            $prevParams = array_merge($queryParams, [
                'page' => $paginator->currentPage() - 1,
                'per_page' => $perPage,
            ]);
            $prevUrl = $baseUrl . '?' . http_build_query($prevParams);
        }

        $filters = array_merge([
            'page'     => $page,
            'per_page' => $perPage,
        ], $filters);

        // ----- Final response -----
        return response()->json([
            'success' => true,
            'message' => 'Products fetched successfully',
            'data' => [
                'products'   => $products,
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page'     => $paginator->perPage(),
                    'from'         => $paginator->firstItem(),
                    'to'           => $paginator->lastItem(),
                    'total'        => $paginator->total(),
                    'total_pages'  => $paginator->lastPage(),
                    'has_more'     => $paginator->hasMorePages(),
                ],
                'filters' => $filters,
                'links'   => [
                    'self'     => $selfUrl,
                    'next'     => $nextUrl,
                    'previous' => $prevUrl,
                ]
            ],
        ], 200);
    }


    public function productDetails(Request $request, $identifier)
    {
        // ----- Authentication -----
        $apiKey     = $request->header('api_key');
        $apiSecret  = $request->header('api_secret');
        $apiUsername = $request->header('username');

        if (!$apiKey || !$apiSecret || !$apiUsername) {
            return response()->json([
                'success' => false,
                'message' => 'Missing authentication headers: api_key, api_secret, and username are required.',
            ], 401);
        }

        $dropshipper = Dropshipper::where('app_key', $apiKey)
            ->where('app_secret', $apiSecret)
            ->where('user_name', $apiUsername)
            ->first();

        if (!$dropshipper) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API credentials.',
            ], 401);
        }

        if ($dropshipper->is_approved != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Your account is not approved. Please contact support.',
            ], 403);
        }

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
                    'data'    => null
                ], 404);
            }

            // ----- Prepare gallery images -----
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
                $attributeValue = json_decode($variant->attribute_value, true) ?? [];
                if (array_key_exists('Attribute', $attributeValue)) {
                    $attributeValue['Age'] = $attributeValue['Attribute'];
                    unset($attributeValue['Attribute']);
                }

                // Get color name if we have a color relation
                $colorName = null;
                if ($variant->color) {
                    $color = Color::find($variant->color);
                    $colorName = $color ? $color->name : null;
                }

                return [
                    'id'              => $variant->id,
                    'sku'             => $variant->sku ?? 'N/A',
                    'price'           => $variant->price,
                    'wholesale_price' => $variant->wholesale_price ?? 0,
                    'stock'           => $variant->quantity ?? 0,
                    'image'           => $variant->image ? uploaded_asset($variant->image) : null,
                    'attribute_value' => $attributeValue,
                ];
            })->values();

            $groupedVariants = [];

            foreach ($variants as $variant) {
                $attributeValue = $variant['attribute_value'] ?? [];
                if (!is_array($attributeValue) || empty($attributeValue)) {
                    continue;
                }

                foreach ($attributeValue as $attrName => $value) {
                    if ($value === null || $value === '') {
                        continue;
                    }

                    if (!isset($groupedVariants[$attrName])) {
                        $groupedVariants[$attrName] = [];
                    }

                    $groupedVariants[$attrName][] = [
                        'id'    => $variant['id'],
                        'sku'   => $variant['sku'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                        'image' => $variant['image'],
                        'value' => $value,
                    ];
                }
            }
            $groupedVariants = collect($groupedVariants)->map(function ($values, $name) {
                return [
                    'name'   => $name,
                    'values' => collect($values)->values(),
                ];
            })->values();


            $reviews = $product->reviews;
            $reviewData = [
                'rating'        => $product->rating ?? 0,
                'reviews_count' => $product->reviews_count ?? 0,
                'items'         => $reviews ? $reviews->map(function ($review) {
                    return [
                        'id'           => $review->id,
                        'rating'       => $review->rating,
                        'reviews_count' => $review->reviews_count ?? 0,
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

                // ***** FIXED PRICE LOGIC *****
                'price' => [
                    'wholesale_price' => $product->variants->isNotEmpty()
                        ? $product->variants->max('wholesale_price')
                        : $product->price->wholesale_price,

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

                'variants'        => $variants, // all variants with "Age" key
                'has_variants'    => $product->variants->count() > 0,
                'review'          => $reviewData,
                'created_at'      => $product->created_at->toDateTimeString(),
                'updated_at'      => $product->updated_at->toDateTimeString(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully',
                'data'    => [
                    'product' => $formattedProduct,
                    'related_products' => []
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


    public function productDetailsFree(Request $request, $identifier)
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
                    'data'    => null
                ], 404);
            }

            // ----- Prepare gallery images -----
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
                // Decode attribute_value as associative array
                $attributeValue = json_decode($variant->attribute_value, true) ?? [];

                if (array_key_exists('Attribute', $attributeValue)) {
                    $attributeValue['Age'] = $attributeValue['Attribute'];
                    unset($attributeValue['Attribute']);
                }

                // Get color name if we have a color relation
                $colorName = null;
                if ($variant->color) {
                    $color = Color::find($variant->color);
                    $colorName = $color ? $color->name : null;
                }

                return [
                    'id'              => $variant->id,
                    'sku'             => $variant->sku ?? 'N/A',
                    'price'           => $variant->price,
                    'wholesale_price' => $variant->wholesale_price ?? 0,
                    'stock'           => $variant->quantity ?? 0,
                    'image'           => $variant->image ? uploaded_asset($variant->image) : null,
                    'attribute_value' => $attributeValue,
                ];
            })->values();

            $groupedVariants = [];

            foreach ($variants as $variant) {
                $attributeValue = $variant['attribute_value'] ?? [];
                if (!is_array($attributeValue) || empty($attributeValue)) {
                    continue;
                }

                foreach ($attributeValue as $attrName => $value) {
                    if ($value === null || $value === '') {
                        continue;
                    }

                    if (!isset($groupedVariants[$attrName])) {
                        $groupedVariants[$attrName] = [];
                    }

                    $groupedVariants[$attrName][] = [
                        'id'    => $variant['id'],
                        'sku'   => $variant['sku'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                        'image' => $variant['image'],
                        'value' => $value,
                    ];
                }
            }
            $groupedVariants = collect($groupedVariants)->map(function ($values, $name) {
                return [
                    'name'   => $name,
                    'values' => collect($values)->values(),
                ];
            })->values();

            // ----- Reviews -----
            $reviews = $product->reviews;
            $reviewData = [
                'rating'        => $product->rating ?? 0,
                'reviews_count' => $product->reviews_count ?? 0,
                'items'         => $reviews ? $reviews->map(function ($review) {
                    return [
                        'id'           => $review->id,
                        'rating'       => $review->rating,
                        'reviews_count' => $review->reviews_count ?? 0,
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

            // ----- Final product array (no attributes, just variants) -----
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
                    'wholesale_price' => $product->variants->isNotEmpty()
                        ? $product->variants->max('wholesale_price')
                        : $product->price->wholesale_price,

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

                'variants'        => $variants, // all variants with "Age" key
                'has_variants'    => $product->variants->count() > 0,
                'review'          => $reviewData,
                'created_at'      => $product->created_at->toDateTimeString(),
                'updated_at'      => $product->updated_at->toDateTimeString(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully',
                'data'    => [
                    'product' => $formattedProduct,
                    'related_products' => []
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
     * Place an order from a dropshipper.
     * Expects authentication headers: api_key, api_secret.
     */
    public function dropshipperPlaceOrder(Request $request)
    {
        $apiKey = $request->header('api_key');
        $apiSecret = $request->header('api_secret');
        $apiUsername = $request->header('username');

        if (!$apiKey || !$apiSecret || !$apiUsername) {
            return response()->json([
                'success' => false,
                'message' => 'Missing authentication headers: api_key and api_secret are required.',
            ], 401);
        }

        // step 1: Authenticate the dropshipper
        $dropshipper = Dropshipper::where('app_key', $apiKey)
            ->where('app_secret', $apiSecret)
            ->where('user_name', $apiUsername)
            ->first();

        if (!$dropshipper) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API credentials.',
            ], 401);
        }

        // step 2: get dropshipper information
        $dropshipperInfoResponse = Http::withHeaders([
            'App-Secret' => $dropshipper->app_secret,
            'App-Key'    => $dropshipper->app_key,
            'Username'   => $dropshipper->user_name,
        ])->get('http://dropshipper.nittoz.com/api/dropshipper/info');

        if (!$dropshipperInfoResponse->ok()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch dropshipper info.',
                'details' => $dropshipperInfoResponse->body()
            ], $dropshipperInfoResponse->status());
        }
        $dropshipperData = $dropshipperInfoResponse['dropshipper'] ?? null;

        if (!$dropshipperData) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dropshipper info is invalid.'
            ], 400);
        }

        // Step 3: Minimum delivery cost
        if ($request->delivery_cost < 60) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Delivery cost must be at least 60.'
            ], 400);
        }

        // Step 4: Calculate deduction before saving order
        $totalWholesaleCost = 0;
        $totalSellingPrice = 0;

        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);

            if ($product) {
                $wholesalePrice = $product->price->wholesale_price ?? 0;
                $sellingPrice = $productData['price'];
                $quantity = $productData['qty'];
                if ($product->is_variant == 1) {
                    $variantWholesale = null;
                    $sku = $productData['variant_sku'] ?? null;

                    if ($sku) {
                        $variant = ProductVarient::where('sku', $sku)->first();

                        if ($variant) {
                            $variantWholesale = $variant->wholesale_price;
                        }
                    }

                    // Fallback: match by the color-size variation
                    if (is_null($variantWholesale)) {
                        $attributeValue = '';

                        if (!empty($productData['color'])) {
                            $attributeValue .= str_replace(' ', '', $productData['color']);
                        }
                        if (!empty($productData['size'])) {
                            if ($attributeValue !== '') {
                                $attributeValue .= '-';
                            }
                            $attributeValue .= $productData['size'];
                        }

                        if ($attributeValue !== '') {
                            $variant = ProductVarient::where('product_id', $product->id)
                                ->where('attribute_value', $attributeValue)
                                ->first();

                            if ($variant) {
                                $variantWholesale = $variant->wholesale_price;
                            }
                        }
                    }
                    if (!is_null($variantWholesale)) {
                        $wholesalePrice = $variantWholesale;
                    }
                }

                $totalWholesaleCost += (float) $wholesalePrice * $quantity;
                $totalSellingPrice += (float) $sellingPrice * $quantity;
            }
        }

        // Deduct amount = shipping charge + shortfall if total wholesale cost exceeds total selling price
        $shortfall = $totalWholesaleCost - $totalSellingPrice;

        if ($shortfall > 0) {
            $deductAmount = (float) $request->delivery_cost + $shortfall;
        } else {
            $deductAmount = (float) $request->delivery_cost;
        }

        // Step 5: Check if dropshipper has enough balance
        if ((int)$dropshipperData['balance'] < $deductAmount) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Insufficient balance for delivery + wholesale adjustment.',
                'required_amount' => $deductAmount,
                'current_balance' => $dropshipperData['balance'],
            ], 400);
        }

        // Step 6: Check if invoice number already exists (must be unique)
        $existingOrder = Order::where('code', $request->invoice_number)->first();

        if ($existingOrder) {
            return response()->json([
                'status'  => 'error',
                'message' => 'This invoice has already been transferred. Invoice number is not unique.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'invoice_number'        => 'required|string|max:255|unique:orders,code',
            'customer_name'         => 'required|string|max:255',
            'customer_phone'        => 'required|string|max:20',
            'delivery_cost'         => 'required|integer|min:0',
            'customer_address'      => 'required|string',
            'price'                 => 'required|numeric|min:0',
            'discount'              => 'nullable|numeric|min:0',
            'advance'               => 'nullable|numeric|min:0',
            'product_quantity'      => 'nullable|integer|min:1',
            'delivery_charge_type'  => 'nullable|string|max:50',
            'payment_type'          => 'required|in:cod,card,bkash,nagad,rocket',
            'order_type'            => 'required|string|max:50',
            'special_notes'         => 'nullable|string',
            'payment_gateway'       => 'nullable|string|max:100',
            'transaction_id'        => 'nullable|string|max:255',
            'products'              => 'required|array|min:1',
            'products.*.id'         => 'required|exists:products,id',
            'products.*.price'      => 'required|numeric|min:0',
            'products.*.color'      => 'nullable|string',
            'products.*.size'       => 'nullable|string',
            'products.*.qty'        => 'required|integer|min:1',
            'products.*.variant_sku'  => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $subtotal = 0;
        $totalQty = 0;
        $orderProducts = [];

        foreach ($validated['products'] as $productData) {
            $product = Product::find($productData['id']);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => "Product with ID {$productData['id']} not found."
                ], 404);
            }

            $subtotal += $productData['price'] * $productData['qty'];
            $totalQty += $productData['qty'];
            $orderProducts[] = [
                'product_id' => $product->id,
                'price'      => $productData['price'],
                'color'      => $productData['color'] ?? null,
                'size'       => $productData['size'] ?? null,
                'qty'        => $productData['qty'],
                'variant_sku' => $productData['variant_sku'] ?? null,
            ];
        }

        $totalQuantity = $validated['product_quantity'] ?? $totalQty;
        $orderCode = $validated['invoice_number'] ?? 'DROP-' . strtoupper(uniqid());

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id'            => null,
                'guest_id'           => null,
                'dropshipper_id'     => $dropshipper->id,
                'code'               => $orderCode,
                'shipping_address'   => $validated['customer_address'],
                'shipping_cost'      => $validated['delivery_cost'],
                'shipping_type'      => $validated['delivery_charge_type'] ?? 'flat_rate',
                'coupon_discount'    => 0,
                'discount'           => $validated['discount'] ?? 0,
                'grand_total'        => $validated['price'] + $validated['delivery_cost'] - ($validated['discount'] ?? 0) - ($validated['advance'] ?? 0),
                'name'               => $validated['customer_name'],
                'email_address'      => null,
                'phone_number'       => $validated['customer_phone'],
                'payment_type'       => $validated['payment_type'],
                'payment_status'     => 'unpaid',
                'delivery_status'    => 'pending',
                'date'               => now(),
                'notes'              => $validated['special_notes'] ?? null,
                'order_type'         => $validated['order_type'] ?? 'Dropshipping',
                'transaction_id'     => $validated['transaction_id'] ?? null,
                'payment_gateway'    => $validated['payment_gateway'] ?? null,
                'advance'            => $validated['advance'] ?? 0,
                'product_quantity'   => $totalQuantity,
            ]);

            foreach ($orderProducts as $op) {
                OrderDetail::create([
                    'order_id'            => $order->id,
                    'seller_id'           => null,
                    'product_id'          => $op['product_id'],
                    'sku'                 => $op['variant_sku'] ?? null,
                    'variation'           => json_encode([
                        'color' => $op['color'],
                        'size'  => $op['size'],
                    ]),
                    'price'               => $op['price'],
                    'tax'                 => 0,
                    'shipping_cost'       => 0,
                    'quantity'            => $op['qty'],
                    'payment_status'      => 'unpaid',
                    'delivery_status'     => 'pending',
                    'shipping_type'       => $validated['delivery_charge_type'] ?? 'flat_rate',
                    'product_referral_code' => null,
                ]);
            }

            DB::commit();

            // Step 7: Deduct final amount
            $balanceResponse = Http::withHeaders([
                'App-Secret' => $dropshipper->app_secret,
                'App-Key'    => $dropshipper->app_key,
                'Username'   => $dropshipper->user_name,
            ])->post('https://dropshipper.nittoz.com/api/dropshipper/update-balance', [
                'amount'         => $deductAmount,
                'type'           => 'debit',
                'reason'         => 'Delivery + wholesale adjustment for invoice #' . $order->code,
                'invoice_number' => $order->code,
            ]);

            if (!$balanceResponse->ok()) {
                Log::warning('Failed to deduct balance.', [
                    'invoice' => $order->code,
                    'status'  => $balanceResponse->status(),
                    'body'    => $balanceResponse->body()
                ]);
            }

            return response()->json([
                'status'   => 'success',
                'message'  => $order->wasRecentlyCreated ? 'Order created successfully.' : 'Order updated successfully.',
                'order_id' => $order->id,
                'deducted' => $deductAmount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }



    private function authenticateDropshipper(Request $request)
    {
        $apiKey = $request->header('api_key');
        $apiSecret = $request->header('api_secret');
        $apiUsername = $request->header('username');

        if (!$apiKey || !$apiSecret || !$apiUsername) {
            return response()->json([
                'success' => false,
                'message' => 'Missing authentication headers: api_key, api_secret, and username are required.',
            ], 401);
        }

        $dropshipper = Dropshipper::where('app_key', $apiKey)
            ->where('app_secret', $apiSecret)
            ->where('user_name', $apiUsername)
            ->first();

        if (!$dropshipper) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API credentials.',
            ], 401);
        }

        return $dropshipper;
    }

    private function calculateDiscountPercentage($product)
    {
        $regularPrice = $product->price->regular_price ?? 0;
        $salePrice = $product->price->sale_price ?? null;

        if ($salePrice && $salePrice < $regularPrice && $regularPrice > 0) {
            return round((($regularPrice - $salePrice) / $regularPrice) * 100);
        }
        return 0;
    }
    private function getCurrentPrice($product)
    {
        $regularPrice = $product->price->regular_price ?? 0;
        $salePrice = $product->price->sale_price ?? null;

        if ($salePrice && $salePrice < $regularPrice) {
            return $salePrice;
        }
        return $regularPrice;
    }
}
