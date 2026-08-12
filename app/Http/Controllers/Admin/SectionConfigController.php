<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SectionConfig;
use Illuminate\Http\Request;

class SectionConfigController extends Controller
{
    /**
     * Display brand list
     */
    public function index()
    {
        $sectionconfigs = SectionConfig::orderBy('order', 'asc')
            ->latest()
            ->paginate(10);

        return view('backend.sectionconfig.index', compact('sectionconfigs'));
    }

    public function updateTitle(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:section_configs,id',
            'title' => 'required|string|max:255',
        ]);

        $section = SectionConfig::find($request->id);
        $section->title = $request->title;
        $section->save();

        return response()->json([
            'success' => true,
            'message' => 'Title updated successfully!',
            'title' => $section->title,
        ]);
    }



    public function sort(Request $request)
    {
        foreach ($request->orders as $item) {
            \App\Models\Admin\SectionConfig::where('id', $item['id'])
                ->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }


    public function toggleStatus(Request $request)
    {
        $section = SectionConfig::findOrFail($request->id);
        $section->isActive = $request->isActive;
        $section->save();

        return response()->json(['success' => true]);
    }
}
