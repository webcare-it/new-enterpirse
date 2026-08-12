<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use App\Models\Admin\Color;
use App\Models\Admin\Attribute;
use App\Models\Admin\AttributeValue;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\ProductInventory;
use App\Models\Admin\ProductPrice;
use App\Models\Admin\ProductSeo;
use App\Models\Admin\ProductShipping;
use App\Models\Admin\ProductTax;
use App\Models\Admin\ProductVarient;
use App\Models\Admin\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;


class ProductController extends Controller
{

    public function index(Request $request)
    {
        $perPage = min((int) $request->get('per_page', 15), 100);
        $query = Product::query()
            ->with(['category', 'brand', 'inventory', 'variants'])
            ->select([
                'id',
                'name',
                'slug',
                'category_id',
                'brand_id',
                'status',
                'is_published',
                'is_featured',
                'is_variant',
                'num_of_sale',
                'thumbnail',
                'created_at',
            ]);

        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = trim($request->search);
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('tags', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhereHas('inventory', function ($inv) use ($search) {
                        $inv->where('sku', 'like', "%{$search}%");
                    })
                    ->orWhereHas('variants', function ($variant) use ($search) {
                        $variant->where('sku', 'like', "%{$search}%");
                    });
            });
        });

        $query->when(
            $request->filled('category_id'),
            fn($q) =>
            $q->where('category_id', $request->category_id)
        );

        $query->when(
            $request->filled('brand_id'),
            fn($q) =>
            $q->where('brand_id', $request->brand_id)
        );

        $query->when(
            $request->filled('status'),
            fn($q) =>
            $q->where('status', $request->status)
        );

        $query->when(
            $request->filled('is_published'),
            fn($q) =>
            $q->where('is_published', $request->is_published)
        );

        $query->when(
            $request->filled('is_featured'),
            fn($q) =>
            $q->where('is_featured', $request->is_featured)
        );

        $query->when(
            $request->filled('is_variant'),
            fn($q) =>
            $q->where('is_variant', $request->is_variant)
        );

        match ($request->get('sort_by', 'latest')) {
            'oldest'     => $query->oldest(),
            'name_asc'   => $query->orderBy('name', 'asc'),
            'name_desc'  => $query->orderBy('name', 'desc'),
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'sale_count' => $query->orderBy('num_of_sale', 'desc'),
            default      => $query->latest(),
        };

        $products = $query
            ->paginate($perPage)
            ->withQueryString();

        $categories = Category::select('id', 'category_name')
            ->orderBy('category_name')
            ->get();

        $brands = Brand::select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('backend.product.products.index', compact(
            'products',
            'categories',
            'brands'
        ));
    }


    public function create()
    {
        $categories = Category::latest()->get();
        $brands = Brand::latest()->get();
        $colors = Color::latest()->get();
        $attributes = Attribute::latest()->get();
        return view('backend.product.products.create', compact('categories', 'brands', 'colors', 'attributes'));
    }

    /**
     * Get subcategories for a given category (AJAX)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubcategories(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id'
        ]);

        $subcategories = SubCategory::where('category_id', $request->category_id)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $subcategories
        ]);
    }



    /**
     * Add more choice option for product attributes (AJAX)
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addMoreChoiceOption(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id'
        ]);

        $attribute = Attribute::find($request->attribute_id);

        // If you have attribute values table
        $attributeValues = AttributeValue::where('attribute_id', $attribute->id)->get();

        if ($attributeValues->count() > 0) {
            $html = '';
            foreach ($attributeValues as $value) {
                $html .= '<option value="' . e($value->value) . '">' . e($value->value) . '</option>';
            }
            return $html;
        }

        // If no predefined values, return empty (will use tag input)
        return '';
    }

    /**
     * Generate SKU combinations for product variants (AJAX)
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function skuCombination(Request $request)
    {
        // Extract input data
        $colorsActive = $request->has('colors_active');
        $colors = $colorsActive ? $request->input('colors', []) : [];
        $unitPrice = (float) $request->input('unit_price', 0);
        $productName = $request->input('name', '');

        // Get selected attributes and their values – key by attribute ID
        $choiceAttributes = $request->input('choice_attributes', []);
        $choiceOptions = [];
        foreach ($choiceAttributes as $attributeId) {
            $choiceOptions[$attributeId] = $request->input("choice_options_{$attributeId}", []);
        }

        // Generate combinations using the fixed method that preloads names
        $combinations = $this->generateCombinations($choiceOptions, $colors, $colorsActive);

        // Build HTML table
        $html = '';
        if (!empty($combinations)) {
            $html .= '<table class="table table-bordered">';
            $html .= '<thead>
                    <tr>
                        <th>' . translate('Variant') . '</th>
                        <th>' . translate('Variant Price') . '</th>
                        <th>' . translate('Wholesale Price') . '</th>
                        <th>' . translate('SKU') . '</th>
                        <th>' . translate('Quantity') . '</th>
                        <th>' . translate('Image') . '</th>
                        <th>' . translate('Action') . '</th>
                    </tr>
                  </thead>
                  <tbody>';

            foreach ($combinations as $key => $combination) {
                // Build human-readable variant name
                $variantNameParts = [];
                foreach ($combination as $attrName => $attrValue) {
                    $variantNameParts[] = $attrName . ':' . $attrValue;
                }
                $variantName = implode(' ', $variantNameParts);

                // Generate a SKU from product name and the combination
                $sku = $this->generateSku($productName, $combination);

                $html .= '<tr class="variant">
                        <td>
                            <input type="hidden"
                                name="variant_attributes[' . $key . '][attributes]"
                                value="' . e(json_encode($combination)) . '">
                            <strong>' . e($variantName) . '</strong>
                        </td>
                        <td>
                            <input type="number" name="variant_attributes[' . $key . '][price]" 
                                value="' . $unitPrice . '" step="0.01" class="form-control" required>
                        </td>
                        <td>
                            <input type="number" name="variant_attributes[' . $key . '][wholesale_price]" 
                                value="' . $unitPrice . '" step="0.01" class="form-control" required>
                        </td>
                        <td>
                            <input type="text" name="variant_attributes[' . $key . '][sku]" 
                                value="' . $sku . '" class="form-control" required>
                        </td>
                        <td>
                            <input type="number" name="variant_attributes[' . $key . '][quantity]" 
                                value="0" min="0" class="form-control" required>
                        </td>
                        <td>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        ' . translate('Browse') . '
                                    </div>
                                </div>
                                <div class="form-control file-amount">' . translate('Choose File') . '</div>
                                <input type="hidden" name="variant_attributes[' . $key . '][image]" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger" onclick="delete_variant(this)">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 6h18"/>
                                    <path d="M8 6V4h8v2"/>
                                    <path d="M19 6l-1 14H6L5 6"/>
                                    <path d="M10 11v6"/>
                                    <path d="M14 11v6"/>
                                </svg>
                            </button>
                        </td>
                      </tr>';
            }

            $html .= '</tbody></table>';
        } else {
            $html = '<div class="alert alert-info">' . translate('No variants selected. Please select attributes or colors.') . '</div>';
        }

        return $html;
    }

    /**
     * Generate all possible combinations of attributes and colors
     * 
     * @param array $choice_options
     * @param array $colors
     * @param bool $colors_active
     * @return array
     */
    private function generateCombinations($choice_options, $colors, $colors_active)
    {
        $combinations = [[]];

        // Preload attribute names
        $attributeIds = array_keys($choice_options);
        $attributeMap = [];
        if (!empty($attributeIds)) {
            $attributeMap = Attribute::whereIn('id', $attributeIds)->pluck('name', 'id')->toArray();
        }

        // Add color combinations if active
        if ($colors_active && !empty($colors)) {
            $new_combinations = [];
            foreach ($combinations as $combination) {
                foreach ($colors as $color) {
                    $colorObj = Color::where('code', $color)->first(); // This also has N+1; we could preload colors too, but probably negligible
                    $colorName = $colorObj ? $colorObj->name : $color;
                    $new_combinations[] = array_merge($combination, ['Color' => $colorName]);
                }
            }
            $combinations = $new_combinations;
        }

        // Add attribute combinations
        foreach ($choice_options as $attribute_index => $options) {
            if (empty($options)) continue;

            $attributeName = $attributeMap[$attribute_index] ?? 'Attribute';
            $new_combinations = [];
            foreach ($combinations as $combination) {
                foreach ($options as $option) {
                    $new_combinations[] = array_merge($combination, [$attributeName => $option]);
                }
            }
            $combinations = $new_combinations;
        }

        return $combinations;
    }

    /**
     * Generate SKU from product name and variant attributes
     * 
     * @param string $product_name
     * @param array $combination
     * @return string
     */
    private function generateSku($product_name, $combination = [])
    {
        // Create base SKU from product name (first 3 letters + random number)
        $base = substr(preg_replace('/[^A-Za-z0-9]/', '', $product_name), 0, 3);
        $base = strtoupper($base) . rand(100, 999);

        // Add attribute codes
        foreach ($combination as $attr_name => $attr_value) {
            $base .= '-' . substr(preg_replace('/[^A-Za-z0-9]/', '', $attr_value), 0, 3);
        }

        return strtoupper($base);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'regular_price' => 'required|numeric|min:0',
            'wholesale_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'thumbnail_img' => 'required|string',
            'description' => 'nullable|string'
        ]);

        try {
            $slug = $request->slug ?: Str::slug($request->name);
            $photos = $request->photos ? json_encode(explode(',', $request->photos)) : null;
            $tags = $request->tags ? json_encode($request->tags) : null;

            $button = $request->input('button', 'draft');
            $is_published = $button === 'publish';
            $status = $button === 'draft' ? 0 : 1;

            // Create main product
            $product = Product::create([
                'name' => $request->name,
                'slug' => $slug,
                'added_by' => auth()->id(),
                'vendor_id' => null,
                'user_id' => auth()->id(),
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'thumbnail' => $request->thumbnail_img,
                'photos' => $photos,
                'tags' => $tags,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'status' => $status,
                'is_published' => $is_published,
                'is_cat' => false,
                'is_featured' => $request->has('is_featured'),
                'best_selling' => $request->has('best_selling'),
                'is_new_arrival' => $request->has('is_new_arrival'),
                'unit' => $request->unit,
                'barcode' => $request->barcode,
                'num_of_sale' => 0,
                'video_link' => $request->video_link,
                'is_variant' => $request->has('is_variant'),
                'badge_name' => $request->badge_name,
                'batch_no' => $request->batch_no,
                'todays_deal' => $request->todays_deal ?? 0,
                'position' => $request->position ?? 0,
                'stock_request' => 0,
            ]);

            // Create inventory
            ProductInventory::create([
                'product_id' => $product->id,
                'sku' => $request->sku ?: $this->generateSku($request->name),
                'barcode' => $request->barcode,
                'stock' => $request->stock,
                'low_stock_qty' => $request->low_stock_qty ?? 1,
                'track_inventory' => $request->has('track_inventory'),
            ]);

            // Handle variants
            if ($request->has('is_variant') && $request->has('variant_attributes')) {
                // Get the attribute ID(s) from the request
                $attributeIds = $request->choice_attributes ?? [];
                $attributeId = !empty($attributeIds) ? $attributeIds[0] : null; // use first attribute

                // Fetch all colors and map by name for quick lookup
                $colorsMap = Color::all()->keyBy('name'); // assuming 'name' column holds the color name

                foreach ($request->variant_attributes as $index => $variant) {
                    if (empty($variant['sku'])) {
                        continue;
                    }

                    // Decode attributes to extract color and attribute value for other columns
                    $attrs = [];
                    if (!empty($variant['attributes'])) {
                        $attrs = is_array($variant['attributes'])
                            ? $variant['attributes']
                            : json_decode($variant['attributes'], true);
                    }

                    $colorName = $attrs['Color'] ?? null;
                    $attributeValue = $attrs['Attribute'] ?? null; // e.g. "1/2 Age"

                    // Find color ID from the mapping
                    $colorId = null;
                    if ($colorName && isset($colorsMap[$colorName])) {
                        $colorId = $colorsMap[$colorName]->id;
                    }

                    ProductVarient::create([
                        'product_id'      => $product->id,
                        'attribute'       => $attributeId,                  // attribute ID (foreign key)
                        'color'           => $colorId,                      // color ID (foreign key)
                        'attribute_value' => $variant['attributes'],        // store the full JSON string
                        'sku'             => $variant['sku'],
                        'price'           => $variant['price'] ?? $request->regular_price,
                        'wholesale_price' => $variant['wholesale_price'] ?? $request->wholesale_price,
                        'quantity'        => $variant['quantity'] ?? 0,
                        'image'           => $variant['image'] ?? null,
                        'material'        => null,
                        'discount_type'   => $request->discount_type,
                        'discount'        => $request->discount ?? 0,
                    ]);
                }
            }

            // Handle price
            $discount_start = $discount_end = null;
            if ($request->date_range) {
                $dates = explode(' to ', $request->date_range);
                if (count($dates) == 2) {
                    $discount_start = Carbon::parse($dates[0]);
                    $discount_end = Carbon::parse($dates[1]);
                }
            }

            $regularPrice = (float) $request->regular_price;
            $wholesalePrice = (float) $request->wholesale_price;
            $discount = (float) ($request->discount ?? 0);
            $salePrice = $request->discount_type === 'flat'
                ? max(0, $regularPrice - $discount)
                : max(0, $regularPrice - (($regularPrice * $discount) / 100));

            ProductPrice::create([
                'product_id'      => $product->id,
                'purchase_price'  => $request->purchase_price ?? 0,
                'regular_price'   => (int) $regularPrice,
                'wholesale_price'   => (int) $wholesalePrice,
                'sale_price'      => (int) round($salePrice),
                'discount_type'   => $request->discount_type,
                'discount'        => $discount,
                'discount_start'  => $discount_start,
                'discount_end'    => $discount_end,
                'currency'        => 'BDT',
            ]);

            // Shipping
            ProductShipping::create([
                'product_id' => $product->id,
                'shipping_type' => $request->shipping_type ?? 'flat_rate',
                'shipping_cost' => $request->shipping_cost ?? 0,
                'weight' => $request->weight,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
            ]);

            // SEO
            ProductSeo::create([
                'product_id' => $product->id,
                'meta_title' => $request->meta_title,
                'meta_image' => $request->meta_img,
                'meta_description' => $request->meta_description,
            ]);

            // Taxes
            if ($request->has('tax_names') && is_array($request->tax_names)) {
                foreach ($request->tax_names as $index => $taxName) {
                    if (!empty($taxName) && isset($request->tax_values[$index])) {
                        ProductTax::create([
                            'product_id' => $product->id,
                            'tax_id' => 1,
                            'tax_type' => $request->tax_types[$index] ?? 'percent',
                        ]);
                    }
                }
            }

            $message = $button === 'publish' ? 'Product published successfully!' : 'Product saved as draft!';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'product_id' => $product->id,
                    'redirect_url' => route('products.index')
                ]);
            }

            flash(translate($message))->success();
            return redirect()->route('products.index');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }

            flash(translate('Error: ' . $e->getMessage()))->error();
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $product = Product::with([
            'inventory',
            'variants',
            'price',
            'shipping',
            'seo',
            'taxes',
            'category',
            'subcategory',
            'brand'
        ])->findOrFail($id);

        // Categories
        $categories = Category::latest()->get();

        // Subcategories
        $subcategories = SubCategory::where(
            'category_id',
            $product->category_id
        )->get();

        // Brands
        $brands = Brand::latest()->get();

        // Colors
        $colors = Color::latest()->get();

        // Selected colors
        $selected_colors = [];

        if (!empty($product->colors)) {

            $selected_colors = json_decode($product->colors, true);

            if (!is_array($selected_colors)) {
                $selected_colors = [];
            }
        }

        // Attributes
        $attributes = Attribute::with('values')->latest()->get();

        // Selected attributes
        $selected_attributes = [];

        if (!empty($product->choice_attributes)) {

            $selected_attributes = json_decode(
                $product->choice_attributes,
                true
            );

            if (!is_array($selected_attributes)) {
                $selected_attributes = [];
            }
        }

        // Existing attribute values
        $existing_attributes = [];

        if (!empty($product->choice_options)) {

            $choice_options = json_decode(
                $product->choice_options,
                true
            );

            if (is_array($choice_options)) {

                foreach ($choice_options as $option) {

                    if (
                        isset($option['attribute_id']) &&
                        isset($option['values'])
                    ) {

                        $existing_attributes[$option['attribute_id']] =
                            $option['values'];
                    }
                }
            }
        }

        // Generate variant combinations
        $combinations = $this->generateVariantCombinations($product);

        return view('backend.product.products.edit', compact(
            'product',
            'categories',
            'subcategories',
            'brands',
            'colors',
            'selected_colors',
            'attributes',
            'selected_attributes',
            'existing_attributes',
            'combinations'
        ));
    }

    private function generateVariantCombinations($product)
    {
        $combinations = [];
        if ($product->variants && $product->variants->count() > 0) {
            foreach ($product->variants as $variant) {
                $combinations[] = [
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'wholesale_price' => $variant->wholesale_price,
                    'attribute_value' => $variant->attribute_value,
                    'quantity' => $variant->quantity,
                    'image' => $variant->image,
                    'attributes' => $variant->attribute
                ];
            }
        }
        return $combinations;
    }


    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $id,
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'regular_price' => 'required|numeric|min:0',
            'wholesale_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'thumbnail_img' => 'required|string',
            'description' => 'nullable|string',
        ]);


        // 1. Get discount parameters
        $discount = $request->input('discount');
        $discountType = $request->input('discount_type'); // 'flat' or 'percent'

        // 2. Load product with price and variants
        $product = Product::with(['price', 'variants'])->findOrFail($id);

        // 3. Apply discount if present
        if ($discount !== null && $discountType !== null && $product->price) {
            $discountValue = (float) $discount;
            $regularPrice = (float) $product->price->regular_price;

            // --- Apply to main product price ---
            if ($discountType === 'flat') {
                $salePrice = max(0, $regularPrice - $discountValue);
                $discountPercentage = ($regularPrice > 0) ? ($discountValue / $regularPrice) * 100 : 0;
            } elseif ($discountType === 'percent') {
                $salePrice = max(0, $regularPrice - ($regularPrice * $discountValue / 100));
                $discountPercentage = $discountValue;
            } else {
                $salePrice = $regularPrice;
                $discountPercentage = 0;
            }

            // Update the product price object
            $product->price->sale_price = number_format($salePrice, 2, '.', '');
            $product->price->current = number_format($salePrice, 2, '.', '');
            $product->price->discount = $discount;
            $product->price->discount_type = $discountType;
            $product->price->discount_percentage = round($discountPercentage, 2);

            // --- Apply the SAME discount to each variant ---
            if ($product->variants->isNotEmpty()) {
                foreach ($product->variants as $variant) {
                    $variantPrice = (float) $variant->price;

                    if ($discountType === 'flat') {
                        $newPrice = max(0, $variantPrice - $discountValue);
                    } elseif ($discountType === 'percent') {
                        $newPrice = max(0, $variantPrice - ($variantPrice * $discountValue / 100));
                    } else {
                        $newPrice = $variantPrice;
                    }

                    // Override the variant's price with the discounted value
                    $variant->price = number_format($newPrice, 2, '.', '');
                }
            }
        }




        try {
            $product = Product::findOrFail($id);
            $slug = $request->slug ?: Str::slug($request->name);
            $photos = $request->photos ? json_encode(explode(',', $request->photos)) : null;
            $tags = $request->tags ? json_encode($request->tags) : null;
            $button = $request->input('button', 'update');
            $is_published = $button === 'update' ? ($request->has('is_published') ? true : false) : false;
            $status = $button === 'draft' ? 0 : 1;

            $product->update([
                'name' => $request->name,
                'slug' => $slug,
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'thumbnail' => $request->thumbnail_img,
                'photos' => $photos,
                'tags' => $tags,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'status' => $status,
                'is_published' => $is_published,
                'is_featured' => $request->has('is_featured') ? true : false,
                'best_selling' => $request->has('best_selling') ? true : false,
                'is_new_arrival' => $request->has('is_new_arrival') ? true : false,
                'unit' => $request->unit,
                'barcode' => $request->barcode,
                'video_link' => $request->video_link,
                'badge_name' => $request->badge_name,
                'batch_no' => $request->batch_no,
                'todays_deal' => $request->todays_deal ?? 0,
                'position' => $request->position ?? 0,
            ]);

            $inventory = $product->inventory;
            if ($inventory) {
                $inventory->update([
                    'sku' => $request->sku ?: $this->generateSku($request->name),
                    'barcode' => $request->barcode,
                    'stock' => $request->stock,
                    'low_stock_qty' => $request->low_stock_qty ?? 1,
                    'track_inventory' => $request->has('track_inventory') ? true : false,
                ]);
            } else {
                ProductInventory::create([
                    'product_id' => $product->id,
                    'sku' => $request->sku ?: $this->generateSku($request->name),
                    'barcode' => $request->barcode,
                    'stock' => $request->stock,
                    'low_stock_qty' => $request->low_stock_qty ?? 1,
                    'track_inventory' => $request->has('track_inventory') ? true : false,
                ]);
            }

            // if ($request->has('is_variant') && $request->has('variant_attributes')) {
            //     ProductVarient::where('product_id', $product->id)->delete();

            //     foreach ($request->variant_attributes as $variant) {
            //         if (!empty($variant['sku'])) {
            //             // Prepare attributes JSON
            //             $attributes_json = null;
            //             if (isset($variant['attributes']) && !empty($variant['attributes'])) {
            //                 if (is_string($variant['attributes'])) {
            //                     $decoded = json_decode($variant['attributes'], true);
            //                     if (json_last_error() === JSON_ERROR_NONE && $decoded !== null) {
            //                         $attributes_json = $variant['attributes'];
            //                     } else {
            //                         $attributes_json = json_encode(['value' => $variant['attributes']]);
            //                     }
            //                 } elseif (is_array($variant['attributes'])) {
            //                     $attributes_json = json_encode($variant['attributes']);
            //                 }
            //             }

            //             ProductVarient::create([
            //                 'product_id' => $product->id,
            //                 'sku' => $variant['sku'],
            //                 'price' => $variant['price'] ?? $request->regular_price,
            //                 'quantity' => $variant['quantity'] ?? 0,
            //                 'image' => $variant['image'] ?? null,
            //                 'attribute' => $attributes_json,
            //                 'material' => null,
            //                 'discount_type' => $request->discount_type ?? null,
            //                 'discount' => $request->discount ?? 0,
            //             ]);
            //         }
            //     }
            // } elseif (!$request->has('is_variant')) {
            //     ProductVarient::where('product_id', $product->id)->delete();
            // }

            // Handle product price - Update or create
            $discount_start = null;
            $discount_end = null;

            if ($request->date_range) {
                $dates = explode(' to ', $request->date_range);
                if (count($dates) == 2) {
                    $discount_start = Carbon::parse($dates[0]);
                    $discount_end = Carbon::parse($dates[1]);
                }
            }

            $price = $product->price;

            $regularPrice = (float) $request->regular_price;
            $WholesalePrice = (float) $request->wholesale_price;
            $discount = (float) ($request->discount ?? 0);

            if ($request->discount_type === 'flat') {
                $salePrice = max(0, $regularPrice - $discount);
            } elseif ($request->discount_type === 'percent') {
                $salePrice = max(0, $regularPrice - (($regularPrice * $discount) / 100));
            } else {
                $salePrice = $regularPrice;
            }

            $salePrice = (int) round($salePrice);

            $price = $product->price;

            if ($price) {
                $price->update([
                    'purchase_price' => $request->purchase_price ?? 0,
                    'regular_price' => (int) $regularPrice,
                    'wholesale_price' => (int) $WholesalePrice,
                    'sale_price' => $salePrice,
                    'discount_type' => $request->discount_type,
                    'discount' => $discount,
                    'discount_start' => $discount_start,
                    'discount_end' => $discount_end,
                ]);
            } else {
                ProductPrice::create([
                    'product_id' => $product->id,
                    'purchase_price' => $request->purchase_price ?? 0,
                    'regular_price' => (int) $regularPrice,
                    'wholesale_price' => (int) $WholesalePrice,
                    'sale_price' => $salePrice,
                    'discount_type' => $request->discount_type,
                    'discount' => $discount,
                    'discount_start' => $discount_start,
                    'discount_end' => $discount_end,
                    'currency' => 'BDT',
                ]);
            }

            // Handle product shipping - Update or create
            $shipping = $product->shipping;
            if ($shipping) {
                $shipping->update([
                    'shipping_type' => $request->shipping_type ?? 'flat_rate',
                    'shipping_cost' => $request->shipping_cost ?? 0,
                    'weight' => $request->weight,
                    'length' => $request->length,
                    'width' => $request->width,
                    'height' => $request->height,
                ]);
            } else {
                ProductShipping::create([
                    'product_id' => $product->id,
                    'shipping_type' => $request->shipping_type ?? 'flat_rate',
                    'shipping_cost' => $request->shipping_cost ?? 0,
                    'weight' => $request->weight,
                    'length' => $request->length,
                    'width' => $request->width,
                    'height' => $request->height,
                ]);
            }

            // Handle SEO - Update or create
            $seo = $product->seo;
            if ($seo) {
                $seo->update([
                    'meta_title' => $request->meta_title,
                    'meta_image' => $request->meta_img,
                    'meta_description' => $request->meta_description,
                ]);
            } else {
                ProductSeo::create([
                    'product_id' => $product->id,
                    'meta_title' => $request->meta_title,
                    'meta_image' => $request->meta_img,
                    'meta_description' => $request->meta_description,
                ]);
            }

            // Handle taxes - Delete existing and recreate
            ProductTax::where('product_id', $product->id)->delete();

            if ($request->has('tax_names') && is_array($request->tax_names)) {
                foreach ($request->tax_names as $index => $taxName) {
                    if (!empty($taxName) && isset($request->tax_values[$index]) && $request->tax_values[$index] > 0) {
                        ProductTax::create([
                            'product_id' => $product->id,
                            'tax_id' => $this->getOrCreateTax($taxName, $request->tax_types[$index] ?? 'percent'),
                            'tax_type' => $request->tax_types[$index] ?? 'percent',
                        ]);
                    }
                }
            }

            // Return response
            $message = 'Product updated successfully!';

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'product_id' => $product->id,
                    'redirect_url' => route('products.index')
                ]);
            }

            flash(translate($message))->success();
            return redirect()->route('products.index');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }

            flash(translate('Error: ' . $e->getMessage()))->error();
            return back()->withInput();
        }
    }

    /**
     * Display the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with([
            'inventory',
            'variants',
            'price',
            'shipping',
            'seo',
            'taxes',
            'category',
            'subcategory',
            'brand',
            'reviews'
        ])->findOrFail($id);

        return view('backend.product.products.show', compact('product'));
    }


    public function updateStatus(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->is_published = $request->status;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product status updated successfully!'
        ]);
    }


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            flash(translate('Product deleted successfully.'))->success();
            return redirect()->route('products.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Bulk delete products
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:products,id'
            ]);

            $count = Product::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $count . ' products deleted successfully!',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update publish status
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:products,id',
                'status' => 'required|in:0,1'
            ]);
            $count = Product::whereIn('id', $request->ids)->update(['is_published' => $request->status]);
            $action = $request->status == 1 ? 'published' : 'unpublished';
            $message = $count . ' product(s) ' . $action . ' successfully!';
            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update featured status
     */
    public function bulkUpdateFeatured(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:products,id',
                'featured' => 'required|in:0,1'
            ]);
            $count = Product::whereIn('id', $request->ids)->update(['is_featured' => $request->featured]);
            $action = $request->featured == 1 ? 'marked as featured' : 'removed from featured';
            return response()->json([
                'success' => true,
                'message' => $count . ' products ' . $action . ' successfully!',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Delete all products with confirmation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAll(Request $request)
    {
        try {
            Product::query()->delete();
            flash(translate('Products deleted successfully.'))->success();
            return redirect()->route('products.index');
        } catch (\Throwable $th) {
            flash($th->getMessage())->error();
            return redirect()->back();
        }
    }
}
