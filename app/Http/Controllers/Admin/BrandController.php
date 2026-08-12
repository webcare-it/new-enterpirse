<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display brand list
     */
    public function index()
    {
        $brands = Brand::orderBy('position', 'asc')
            ->latest()
            ->paginate(10);

        return view('backend.brand.index', compact('brands'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('backend.brand.create');
    }

    /**
     * Store new brand
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:brands,name',
            'brand_image' => 'required',
            'bg_image'    => 'required',
            'description' => 'nullable|string',
        ]);

        $brand = new Brand();
        $bname = $request->name; // slug

        $brand->position = $request->position ?? (Brand::max('position') + 1);
        $brand->brand_image = $request->brand_image;
        $brand->bg_image = $request->bg_image;
        $brand->description = $request->description;
        $brand->name = $bname;

        $brand->save();

        flash(translate('Brand created successfully.'))->success();

        return redirect()->route('brand.index');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);

        return view('backend.brand.edit', compact('brand'));
    }

    /**
     * Update brand
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'slug'        => 'nullable|string|max:255|unique:brands,slug,' . $brand->id,
            'position'    => 'nullable|numeric',
            'brand_image' => 'nullable',
            'bg_image'    => 'nullable',
            'description' => 'nullable|string',
        ]);

        $brand->name = $request->name;

        // slug handled by mutator
        $brand->slug = $request->slug ?: $request->name;

        $brand->position = $request->position ?? $brand->position;

        if ($request->brand_image) {
            $brand->brand_image = $request->brand_image;
        }

        if ($request->bg_image) {
            $brand->bg_image = $request->bg_image;
        }

        $brand->description = $request->description;

        $brand->save();

        flash(translate('Brand updated successfully.'))->success();

        return redirect()->route('brand.index');
    }


    public function sort(Request $request)
    {
        foreach ($request->positions as $item) {
            \App\Models\Admin\Brand::where('id', $item['id'])
                ->update(['position' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Delete brand
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        $brand->delete();

        flash(translate('Brand deleted successfully.'))->success();

        return redirect()->route('brand.index');
    }
}
