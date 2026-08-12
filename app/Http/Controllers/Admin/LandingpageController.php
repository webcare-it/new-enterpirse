<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\LandingPageProduct;
use App\Models\Admin\Product;
use App\Models\Landingpage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LandingpageController extends Controller
{
    /**
     * Display a listing of landing pages
     */
    public function index(Request $request)
    {
        $query = LandingPage::query();

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('slug', 'like', '%' . $request->search . '%');
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('is_published', $request->status == 'published' ? 1 : 0);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $landingPages = $query->paginate(15)->appends($request->query());

        // Statistics
        $totalPages = LandingPage::count();
        $publishedPages = LandingPage::where('is_published', true)->count();
        $draftPages = LandingPage::where('is_published', false)->count();

        return view('backend.landingpages.index', compact('landingPages', 'totalPages', 'publishedPages', 'draftPages'));
    }

    /**
     * Show the form for creating a new landing page
     */
    public function create()
    {
        $products = Product::where('is_published', 1)->get(['id', 'name']);
        return view('backend.landingpages.create', compact('products'));
    }

    /**
     * Store a newly created landing page
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:landingpages,slug',
            'title' => 'nullable|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'banner_image' => 'nullable|string',
            'mobile_banner' => 'nullable|string',
            'video_link' => 'nullable|url',
            'deadline' => 'nullable|date',
            'features' => 'nullable|array',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'testimonials' => 'nullable|array',
            'faq' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_image' => 'nullable|string',
            'sold_count' => 'nullable|integer',
            'visitor_count' => 'nullable|integer',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'whatsapp' => 'nullable|string|max:20',
            'copyright_text' => 'nullable|string',
            'is_published' => 'boolean',
            'products' => 'nullable|array',
            'products.*.product_id' => 'exists:products,id',
            'products.*.regular_price' => 'nullable|numeric|min:0',
            'products.*.discount_price' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Generate slug if not provided
            $slug = $request->slug ?: Str::slug($request->name);

            // Process JSON fields
            $features = $request->features ? json_encode($request->features) : null;
            $testimonials = $request->testimonials ? json_encode($request->testimonials) : null;
            $faq = $request->faq ? json_encode($request->faq) : null;

            // Create landing page
            $landingPage = LandingPage::create([
                'name' => $request->name,
                'slug' => $slug,
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'banner_image' => $request->banner_image,
                'mobile_banner' => $request->mobile_banner,
                'video_link' => $request->video_link,
                'deadline' => $request->deadline,
                'features' => $features,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'testimonials' => $testimonials,
                'faq' => $faq,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_image' => $request->meta_image,
                'sold_count' => $request->sold_count ?? 0,
                'visitor_count' => $request->visitor_count ?? 0,
                'phone' => $request->phone,
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'copyright_text' => $request->copyright_text,
                'is_published' => $request->has('is_published') ? true : false
            ]);

            // Attach products with custom prices
            if ($request->has('products') && !empty($request->products)) {
                foreach ($request->products as $product) {
                    LandingPageProduct::create([
                        'landingpage_id' => $landingPage->id,
                        'product_id' => $product['product_id'],
                        'regular_price' => $product['regular_price'] ?? 0,
                        'discount_price' => $product['discount_price'] ?? null
                    ]);
                }
            }

            DB::commit();

            flash(translate('Landing page created successfully!'))->success();
            return redirect()->route('landingpages.index');
        } catch (\Exception $e) {
            DB::rollBack();
            flash(translate('Error: ' . $e->getMessage()))->error();
            return back()->withInput();
        }
    }

    /**
     * Display the specified landing page (frontend)
     */
    public function show($slug)
    {
        $landingPage = LandingPage::with(['products'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Increment visitor count
        $landingPage->increment('visitor_count');

        return view('backend.landingpages.show', compact('landingPage'));
    }

    /**
     * Show the form for editing the specified landing page
     */
    public function edit($id)
    {
        $landingPage = LandingPage::with('products')->findOrFail($id);

        $products = Product::where('is_published', 1)->get(['id', 'name']);

        // Decode JSON fields
        $landingPage->features = json_decode($landingPage->features, true);
        $landingPage->testimonials = json_decode($landingPage->testimonials, true);
        $landingPage->faq = json_decode($landingPage->faq, true);

        // Get selected products with pivot data
        $selectedProducts = $landingPage->products->map(function ($product) {
            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'regular_price' => $product->pivot->regular_price,
                'discount_price' => $product->pivot->discount_price
            ];
        });

        return view('backend.landingpages.edit', compact('landingPage', 'products', 'selectedProducts'));
    }

    /**
     * Update the specified landing page
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:landingpages,slug,' . $id,
            'title' => 'nullable|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'banner_image' => 'nullable|string',
            'mobile_banner' => 'nullable|string',
            'video_link' => 'nullable|url',
            'deadline' => 'nullable|date',
            'features' => 'nullable|array',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'testimonials' => 'nullable|array',
            'faq' => 'nullable|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_image' => 'nullable|string',
            'sold_count' => 'nullable|integer',
            'visitor_count' => 'nullable|integer',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'whatsapp' => 'nullable|string|max:20',
            'copyright_text' => 'nullable|string',
            'is_published' => 'boolean',
            'products' => 'nullable|array',
            'products.*.product_id' => 'exists:products,id',
            'products.*.regular_price' => 'nullable|numeric|min:0',
            'products.*.discount_price' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $landingPage = LandingPage::findOrFail($id);

            // Generate slug if not provided
            $slug = $request->slug ?: Str::slug($request->name);

            // Process JSON fields
            $features = $request->features ? json_encode($request->features) : null;
            $testimonials = $request->testimonials ? json_encode($request->testimonials) : null;
            $faq = $request->faq ? json_encode($request->faq) : null;

            // Update landing page
            $landingPage->update([
                'name' => $request->name,
                'slug' => $slug,
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'banner_image' => $request->banner_image,
                'mobile_banner' => $request->mobile_banner,
                'video_link' => $request->video_link,
                'deadline' => $request->deadline,
                'features' => $features,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'testimonials' => $testimonials,
                'faq' => $faq,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_image' => $request->meta_image,
                'sold_count' => $request->sold_count ?? 0,
                'visitor_count' => $request->visitor_count ?? 0,
                'phone' => $request->phone,
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'copyright_text' => $request->copyright_text,
                'is_published' => $request->has('is_published') ? true : false
            ]);

            // Update products - remove old and add new
            LandingPageProduct::where('landingpage_id', $landingPage->id)->delete();

            if ($request->has('products') && !empty($request->products)) {
                foreach ($request->products as $product) {
                    LandingPageProduct::create([
                        'landingpage_id' => $landingPage->id,
                        'product_id' => $product['product_id'],
                        'regular_price' => $product['regular_price'] ?? 0,
                        'discount_price' => $product['discount_price'] ?? null
                    ]);
                }
            }

            DB::commit();

            flash(translate('Landing page updated successfully!'))->success();
            return redirect()->route('landingpages.index');
        } catch (\Exception $e) {
            DB::rollBack();
            flash(translate('Error: ' . $e->getMessage()))->error();
            return back()->withInput();
        }
    }

    /**
     * Remove the specified landing page
     */
    public function destroy($id)
    {
        try {
            $landingPage = LandingPage::findOrFail($id);

            // Delete related products first
            LandingPageProduct::where('landingpage_id', $id)->delete();

            // Delete landing page
            $landingPage->delete();

            flash(translate('Landing page deleted successfully!'))->success();
            return redirect()->route('landingpages.index');
        } catch (\Exception $e) {
            flash(translate('Error: ' . $e->getMessage()))->error();
            return back();
        }
    }

    /**
     * Toggle landing page status
     */
    public function toggleStatus($id)
    {
        try {
            $landingPage = LandingPage::findOrFail($id);
            $landingPage->is_published = !$landingPage->is_published;
            $landingPage->save();

            return response()->json([
                'success' => true,
                'message' => 'Landing page status updated successfully!',
                'status' => $landingPage->is_published
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview landing page (admin)
     */
    public function preview($id)
    {
        $landingPage = LandingPage::with('products')->findOrFail($id);
        return view('frontend.landingpages.show', compact('landingPage'));
    }

    /**
     * Bulk delete landing pages
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:landingpages,id'
            ]);

            // Delete related products first
            foreach ($request->ids as $id) {
                LandingPageProduct::where('landingpage_id', $id)->delete();
            }

            $count = LandingPage::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $count . ' landing pages deleted successfully!',
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
     * Bulk update landing page status
     */
    public function bulkStatus(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:landingpages,id',
                'status' => 'required|in:published,draft'
            ]);

            $status = $request->status == 'published' ? 1 : 0;
            $count = LandingPage::whereIn('id', $request->ids)->update(['is_published' => $status]);

            return response()->json([
                'success' => true,
                'message' => $count . ' landing pages updated successfully!',
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
     * Export landing pages to CSV
     */
    public function export()
    {
        $landingPages = LandingPage::with('products')->get();

        $fileName = 'landing_pages_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($landingPages) {
            $file = fopen('php://output', 'w');

            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, ['ID', 'Name', 'Slug', 'Title', 'Products Count', 'Status', 'Sold Count', 'Visitor Count', 'Created At']);

            foreach ($landingPages as $page) {
                fputcsv($file, [
                    $page->id,
                    $page->name,
                    $page->slug,
                    $page->title,
                    $page->products->count(),
                    $page->is_published ? 'Published' : 'Draft',
                    $page->sold_count,
                    $page->visitor_count,
                    $page->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Track visitor count
     */
    public function trackVisitor($id)
    {
        try {
            $landingPage = LandingPage::findOrFail($id);
            $landingPage->increment('visitor_count');

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false
            ]);
        }
    }
}
