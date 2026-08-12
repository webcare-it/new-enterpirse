<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display listing
     */
    public function index()
    {
        $subcategories = SubCategory::with('category')
            ->latest()
            ->paginate(10);

        return view('backend.subcategory.index', compact('subcategories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = Category::all();

        return view('backend.subcategory.create', compact('categories'));
    }

    /**
     * Store new subcategory
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'  => 'required|string|max:255',
            'image' => 'nullable',
        ]);

        $subcategory = new SubCategory();

        $subcategory->category_id = $request->category_id;
        $subcategory->name = $request->name;
        $subcategory->image = $request->image;

        $subcategory->save();

        flash(translate('Subcategory created successfully.'))->success();

        return redirect()->route('subcategory.index');
    }

    /**
     * Edit form
     */
    public function edit($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $categories = Category::all();

        return view('backend.subcategory.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update subcategory
     */
    public function update(Request $request, $id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'  => 'required|string|max:255',
            'slug'  => 'nullable|string|max:255|unique:sub_categories,slug,' . $subcategory->id,
            'image' => 'nullable',
        ]);
        $subcategory->category_id = $request->category_id;
        $subcategory->name = $request->name;
        $subcategory->slug = $request->slug ?: $request->name;

        if ($request->image) {
            $subcategory->image = $request->image;
        }
        $subcategory->save();
        flash(translate('Subcategory updated successfully.'))->success();
        return redirect()->route('subcategory.index');
    }

    /**
     * Delete subcategory
     */
    public function destroy($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $subcategory->delete();
        flash(translate('Subcategory deleted successfully.'))->success();
        return redirect()->route('subcategory.index');
    }
}
