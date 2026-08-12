<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Compare;
use Illuminate\Http\Request;
use App\Exports\CompareExport;
use Maatwebsite\Excel\Facades\Excel;

class CompareController extends Controller
{
    /**
     * Display compare list (Admin)
     */
    public function index(Request $request)
    {
        $query = Compare::with(['user', 'product']);
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('product_id') && $request->product_id != '') {
            $query->where('product_id', $request->product_id);
        }
        $sortBy = $request->get('sort_by', 'latest');
        switch ($sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        $compares = $query->paginate(20)->appends($request->query());

        // Statistics
        $totalCompares = Compare::count();
        $uniqueUsers = Compare::whereNotNull('user_id')->distinct('user_id')->count('user_id');
        $uniqueProducts = Compare::distinct('product_id')->count('product_id');
        $comparesThisWeek = Compare::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        // Count guest compares
        $guestCompares = Compare::whereNull('user_id')->count();

        return view('backend.compares.index', compact(
            'compares',
            'totalCompares',
            'uniqueUsers',
            'uniqueProducts',
            'comparesThisWeek',
            'guestCompares'
        ));
    }

    /**
     * Remove compare record (Admin)
     */
    public function destroy($id)
    {
        try {
            $compare = Compare::findOrFail($id);
            $compare->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Compare record deleted successfully!'
                ]);
            }

            return redirect()->route('compares.index')->with('success', 'Compare record deleted successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete compare records
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:compares,id'
            ]);

            $count = Compare::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $count . ' compare records deleted successfully!',
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
     * Clear all compare records
     */
    public function clearAll()
    {
        try {
            $count = Compare::count();
            Compare::truncate();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $count . ' compare records cleared successfully!'
                ]);
            }

            return redirect()->route('compares.index')->with('success', 'All compare records cleared successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export compare data to CSV
     */
    public function export()
    {
        return Excel::download(
            new CompareExport,
            'compares_' . date('Y-m-d') . '.xlsx'
        );
    }
}
