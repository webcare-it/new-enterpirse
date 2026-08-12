<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class DropshippingCategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Admin\Category::oldest('position')->paginate(10);
        return view('backend.category.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name'   => 'required',
            'category_image'  => 'nullable',
            'hero_image'      => 'nullable',
            'title'           => 'required',
            'sub_title'       => 'required',
            'description'     => 'required',
            'button_name'     => 'required',
            'button_link'     => 'required|url'
        ]);

        $category = new \App\Models\Admin\Category();

        $category->category_name = $request->category_name;
        $slug = Str::slug($request->category_name);

        $count = \App\Models\Admin\Category::where('slug', 'LIKE', "{$slug}%")->count();
        $category->slug = $count ? $slug . '-' . ($count + 1) : $slug;

        $category->title = $request->title;
        $category->category_image = $request->category_image;
        $category->hero_image = $request->hero_image;
        $category->sub_title = $request->sub_title;
        $category->description = $request->description;
        $category->button_name = $request->button_name;
        $category->button_link = $request->button_link;

        $category->save();

        flash(translate('Dropshipping Category created successfully.'))->success();
        return redirect()->route('dropshipping-category.index');
    }

    public function edit($id)
    {
        $category = \App\Models\Admin\Category::findOrFail($id);
        return view('backend.category.edit', compact('category'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name'   => 'required',
            'category_image'  => 'nullable',
            'hero_image'      => 'nullable',
            'title'           => 'required'
        ]);

        $category = \App\Models\Admin\Category::findOrFail($id);
        $category->category_name = $request->category_name;
        $slug = Str::slug($request->category_name);
        $count = \App\Models\Admin\Category::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $id)->count();
        $category->slug = $count ? $slug . '-' . ($count + 1) : $slug;

        $category->title = $request->title;
        $category->category_image = $request->category_image;
        $category->hero_image = $request->hero_image;
        $category->sub_title = $request->sub_title;
        $category->description = $request->description;
        $category->button_name = $request->button_name;
        $category->button_link = $request->button_link;

        $category->save();

        flash(translate('Dropshipping Category updated successfully.'))->success();
        return redirect()->route('dropshipping-category.index');
    }

    public function sort(Request $request)
    {
        foreach ($request->positions as $item) {
            \App\Models\Admin\Category::where('id', $item['id'])
                ->update(['position' => $item['position']]);
        }
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $hasProducts = Product::where('category_id', $category->id)->exists();

        if ($hasProducts) {
            flash(translate('This category cannot be deleted because it contains products.'))->error();
            return redirect()->route('dropshipping-category.index');
        }

        $category->delete();

        flash(translate('Dropshipping Category deleted successfully.'))->success();
        return redirect()->route('dropshipping-category.index');
    }
}
