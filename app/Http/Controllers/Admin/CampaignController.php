<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Campaign;
use App\Models\Admin\CampaignProduct;
use App\Models\Admin\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns
     */
    public function index(Request $request)
    {
        $query = Campaign::with('products');

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'running') {
                $query->running();
            } elseif ($request->status == 'upcoming') {
                $query->upcoming();
            } elseif ($request->status == 'ended') {
                $query->ended();
            } else {
                $query->where('status', $request->status);
            }
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
            case 'start_date_asc':
                $query->orderBy('start_date', 'asc');
                break;
            case 'start_date_desc':
                $query->orderBy('start_date', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $campaigns = $query->paginate(15)->appends($request->query());

        // Statistics
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $runningCampaigns = Campaign::running()->count();
        $upcomingCampaigns = Campaign::upcoming()->count();
        $endedCampaigns = Campaign::ended()->count();
        $totalProductsInCampaigns = CampaignProduct::distinct('product_id')->count('product_id');

        return view('backend.campaigns.index', compact(
            'campaigns',
            'totalCampaigns',
            'activeCampaigns',
            'runningCampaigns',
            'upcomingCampaigns',
            'endedCampaigns',
            'totalProductsInCampaigns'
        ));
    }

    /**
     * Show the form for creating a new campaign
     */
    public function create()
    {
        $productIdsInCampaigns = \DB::table('campaign_products')->pluck('product_id')->unique()->toArray();
        $products = Product::with('price')
            ->whereNotIn('id', $productIdsInCampaigns)
            ->latest()
            ->get();

        return view('backend.campaigns.create', compact('products'));
    }

    /**
     * Store a newly created campaign
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:campaigns,slug',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:flat,percent',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|string',
            'products' => 'nullable'
        ]);

        try {
            DB::beginTransaction();

            // Generate slug if not provided
            $slug = $request->slug ?: Str::slug($request->name);

            // Create campaign
            $campaign = Campaign::create([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'discount_amount' => $request->discount_amount ?? 0,
                'discount_type' => $request->discount_type ?? 'flat',
                'status' => $request->status,
                'image' => $request->image
            ]);

            // Attach products - Decode JSON string if needed
            $products = $request->products;

            // If products is a JSON string, decode it
            if (is_string($products) && !empty($products)) {
                $products = json_decode($products, true);
            }

            // If products is empty or not an array, set as empty array
            if (!is_array($products)) {
                $products = [];
            }

            if (!empty($products)) {
                foreach ($products as $priority => $productId) {
                    CampaignProduct::create([
                        'campaign_id' => $campaign->id,
                        'product_id' => $productId,
                        'priority' => $priority + 1 // Add 1 to make priority start from 1
                    ]);
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Campaign created successfully!',
                    'campaign' => $campaign
                ]);
            }

            flash(translate('Campaign created successfully!'))->success();
            return redirect()->route('campaigns.index');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
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
     * Display the specified campaign
     */
    public function show($id)
    {
        $campaign = Campaign::with(['products', 'products.price', 'products.inventory', 'products.brand'])
            ->findOrFail($id);

        $campaignProducts = CampaignProduct::with('product')
            ->where('campaign_id', $id)
            ->orderBy('priority')
            ->get();

        return view('backend.campaigns.show', compact('campaign', 'campaignProducts'));
    }

    /**
     * Show the form for editing the specified campaign
     */
    public function edit($id)
    {
        $campaign = Campaign::with('products')->findOrFail($id);
        $productIdsInCampaigns = \DB::table('campaign_products')->pluck('product_id')->unique()->toArray();
        $currentProductIds = $campaign->products->pluck('id')->toArray();
        $products = Product::with('price')
            ->whereNotIn('id', $productIdsInCampaigns)
            ->latest()
            ->get();
        $selectedProducts = $currentProductIds;
        return view('backend.campaigns.edit', compact('campaign', 'products', 'selectedProducts'));
    }


    /**
     * Update the specified campaign
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:campaigns,slug,' . $id,
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:flat,percent',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id'
        ]);

        try {
            DB::beginTransaction();

            $campaign = Campaign::findOrFail($id);

            // Generate slug if not provided
            $slug = $request->slug ?: Str::slug($request->name);

            // Update campaign
            $campaign->update([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'discount_amount' => $request->discount_amount ?? 0,
                'discount_type' => $request->discount_type ?? 'flat',
                'status' => $request->status,
                'image' => $request->image
            ]);

            // Update products - sync instead of delete all
            $productIds = $request->has('products') && !empty($request->products)
                ? $request->products
                : [];

            // Prepare sync data with priority
            $syncData = [];
            foreach ($productIds as $priority => $productId) {
                $syncData[$productId] = ['priority' => $priority + 1];
            }

            // Sync products (this will add, remove, and update priorities)
            $campaign->products()->sync($syncData);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Campaign updated successfully!'
                ]);
            }

            flash(translate('Campaign updated successfully!'))->success();
            return redirect()->route('campaigns.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
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
     * Remove the specified campaign
     */
    public function destroy($id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Campaign deleted successfully!'
                ]);
            }

            flash(translate('Campaign deleted successfully!'))->success();
            return redirect()->route('campaigns.index');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }

            flash(translate('Error: ' . $e->getMessage()))->error();
            return back();
        }
    }

    /**
     * Toggle campaign status
     */
    public function toggleStatus($id)
    {
        try {
            $campaign = Campaign::findOrFail($id);

            // Toggle status
            $oldStatus = $campaign->status;
            $campaign->status = $campaign->status == 'active' ? 'inactive' : 'active';
            $campaign->save();

            $statusText = $campaign->status == 'active' ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Campaign {$statusText} successfully!",
                'status' => $campaign->status,
                'old_status' => $oldStatus
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign not found!'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $campaign = Campaign::findOrFail($request->campaign_id);
        $campaign->status = $request->status;
        $campaign->save();

        return response()->json([
            'success' => true,
            'message' => 'Campaign status updated successfully!'
        ]);
    }

    /**
     * Bulk delete campaigns
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:campaigns,id'
            ]);

            $count = Campaign::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $count . ' campaigns deleted successfully!',
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
     * Bulk update campaign status
     */
    public function bulkStatus(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:campaigns,id',
                'status' => 'required|in:active,inactive'
            ]);

            $count = Campaign::whereIn('id', $request->ids)->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => $count . ' campaigns updated successfully!',
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
     * Add product to campaign
     */
    public function addProduct(Request $request, $id)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'priority' => 'nullable|integer'
            ]);

            $campaign = Campaign::findOrFail($id);

            // Check if product already exists
            $exists = CampaignProduct::where('campaign_id', $id)
                ->where('product_id', $request->product_id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product already in this campaign!'
                ], 400);
            }

            CampaignProduct::create([
                'campaign_id' => $id,
                'product_id' => $request->product_id,
                'priority' => $request->priority ?? 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product added to campaign successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove product from campaign
     */
    public function removeProduct($campaignId, $productId)
    {
        try {
            CampaignProduct::where('campaign_id', $campaignId)
                ->where('product_id', $productId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product removed from campaign successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product priority in campaign
     */
    public function updateProductPriority(Request $request, $id)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'priority' => 'required|integer'
            ]);

            CampaignProduct::where('campaign_id', $id)
                ->where('product_id', $request->product_id)
                ->update(['priority' => $request->priority]);

            return response()->json([
                'success' => true,
                'message' => 'Product priority updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search products for AJAX
     */
    public function searchProducts(Request $request)
    {
        $search = $request->get('q', '');

        $products = Product::where('name', 'like', "%{$search}%")
            ->orWhereHas('inventory', function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'thumbnail']);

        return response()->json($products);
    }

    /**
     * Export campaigns to CSV
     */
    public function export()
    {
        $campaigns = Campaign::with('products')->get();

        $fileName = 'campaigns_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($campaigns) {
            $file = fopen('php://output', 'w');

            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, ['ID', 'Name', 'Slug', 'Start Date', 'End Date', 'Discount', 'Discount Type', 'Status', 'Products Count', 'Created At']);

            foreach ($campaigns as $campaign) {
                fputcsv($file, [
                    $campaign->id,
                    $campaign->name,
                    $campaign->slug,
                    $campaign->start_date->format('Y-m-d'),
                    $campaign->end_date->format('Y-m-d'),
                    $campaign->discount_amount ?? 0,
                    $campaign->discount_type ?? 'N/A',
                    $campaign->status,
                    $campaign->products->count(),
                    $campaign->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
