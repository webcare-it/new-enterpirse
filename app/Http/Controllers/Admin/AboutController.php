<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\About;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $aboutItems = About::orderBy('sort_order')->get();
        $counterItems = BusinessSetting::where('type', 'counter_items')->first();
        return view('backend.about.about', compact('aboutItems', 'counterItems'));
    }

    public function update(Request $request)
    {
        $items = array_values($request->input('items', []));
        $request->merge(['items' => $items]);

        $request->validate([
            'items.*.title' => 'required|string|max:255',
            'items.*.sub_title' => 'required|string',
            'items.*.description' => 'required|string',
            'items.*.image' => 'nullable',
            'items.*.image_position' => 'nullable|string|in:left,right',
        ]);

        \App\Models\Admin\About::truncate();

        foreach ($items as $itemData) {
            \App\Models\Admin\About::create([
                'title' => $itemData['title'],
                'sub_title' => $itemData['sub_title'],
                'description' => $itemData['description'],
                'image' => $itemData['image'] ?? null,
                'image_position' => $itemData['image_position'] ?? 'left'
            ]);
        }

        flash(translate('About items updated successfully.'))->success();
        return redirect()->route('about.index');
    }

    public function counter(Request $request)
    {
        $items = array_values($request->input('items', []));
        $request->merge(['items' => $items]);

        $request->validate([
            'items.*.number' => 'required|string|max:255',
            'items.*.title'  => 'required|string|max:255',
        ]);

        $json = json_encode($items);

        BusinessSetting::updateOrCreate(
            ['type' => 'counter_items'],
            ['value' => $json]
        );

        flash(translate('Counter items updated successfully.'))->success();
        return redirect()->route('about.index');
    }
}
