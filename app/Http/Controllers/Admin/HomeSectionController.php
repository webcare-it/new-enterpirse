<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Campaign;
use App\Models\Admin\Blog;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\ProductPrice;
use App\Models\Admin\Section;
use App\Models\Admin\SectionConfig;
use App\Models\Admin\Slider;
use App\Models\BusinessSetting;
use App\Models\Page;
use Illuminate\Http\Request;

class HomeSectionController extends Controller
{
    public function home()
    {
        return view('backend.section.index');
    }
    public function mainPage(Request $request)
    {
        $page = Page::where('slug', 'home')->first();
        $page_name = $request->page;

        $sliders = Slider::all();
        $blogs = Blog::all();
        $categories = Category::all();
        $products = Product::all();
        $campaigns = Campaign::all();
        $sectionconfigs = SectionConfig::orderBy('order')->paginate(20);

        return view('backend.website_settings.pages.home_page', compact('sliders', 'blogs', 'categories', 'products', 'campaigns', 'sectionconfigs'));
    }

    public function mainPageUpdate(Request $request)
    {
        $page = Page::where('slug', 'home')->firstOrFail();
        $slug = $page->slug;
        $data = $request->except(['_token', '_method']);

        $sectionMap = [
            'slider' => 'sliders',
            'discover' => 'discovers',
            'blog' => 'blogs',
        ];

        $section = null;
        $valueData = null;
        foreach ($sectionMap as $key => $valueKey) {
            if (isset($data[$key]) && isset($data[$valueKey])) {
                if (is_array($data[$valueKey])) {
                    $section = $key;
                    $valueData = $data[$valueKey];
                    break;
                }
            }
        }

        if (!$section || !$valueData) {
            return back()->with('error', 'Invalid request data');
        }

        $type = $slug . '_' . $section;

        $value = json_encode(array_values($valueData));

        BusinessSetting::updateOrCreate(
            ['type' => $type],
            ['value' => $value]
        );

        flash(translate('Page section has been updated successfully'))->success();
        return redirect()->route('website.pages');
    }
}
