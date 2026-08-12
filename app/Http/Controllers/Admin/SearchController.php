<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Search;
use App\Exports\SearchesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display search analytics
     */
    public function index(Request $request)
    {
        $query = Search::query();

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $query->where('query', 'like', '%' . $request->search . '%');
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'popular');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('count', 'desc');
                break;
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'query_asc':
                $query->orderBy('query', 'asc');
                break;
            case 'query_desc':
                $query->orderBy('query', 'desc');
                break;
            default:
                $query->orderBy('count', 'desc');
                break;
        }

        $searches = $query->paginate(20)->appends($request->query());

        // Statistics
        $totalSearches = Search::sum('count');
        $uniqueKeywords = Search::count();
        $mostPopularSearch = Search::orderBy('count', 'desc')->first();
        $avgSearchesPerKeyword = $uniqueKeywords > 0 ? round($totalSearches / $uniqueKeywords, 2) : 0;
        $searchesThisWeek = Search::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->sum('count');

        // Top 10 searches
        $topSearches = Search::orderBy('count', 'desc')->limit(10)->get();

        return view('backend.searches.index', compact(
            'searches',
            'totalSearches',
            'uniqueKeywords',
            'mostPopularSearch',
            'avgSearchesPerKeyword',
            'searchesThisWeek',
            'topSearches'
        ));
    }

    /**
     * Remove the specified search
     */
    public function destroy($id)
    {
        try {
            $search = Search::findOrFail($id);
            $search->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Search keyword deleted successfully!'
                ]);
            }

            flash(translate('Search keyword deleted successfully!'))->success();
            return redirect()->route('searches.index');
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
     * Bulk delete searches
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:searches,id'
            ]);

            $count = Search::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => $count . ' search keywords deleted successfully!',
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
     * Clear all search records
     */
    public function clearAll()
    {
        try {
            $count = Search::count();
            Search::truncate();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $count . ' search records cleared successfully!'
                ]);
            }

            flash(translate('All search records cleared successfully!'))->success();
            return redirect()->route('searches.index');
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
     * Export search data to CSV
     */
    public function export()
    {
        return Excel::download(
            new SearchesExport,
            'searches_' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Track search (called from frontend)
     */
    public function track(Request $request)
    {
        try {
            $request->validate([
                'query' => 'required|string|max:255'
            ]);

            Search::incrementCount($request->query);

            return response()->json([
                'success' => true,
                'message' => 'Search tracked successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
