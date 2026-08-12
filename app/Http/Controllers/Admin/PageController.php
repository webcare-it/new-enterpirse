<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Blog;
use App\Models\Admin\Discover;
use App\Models\Admin\Category;
use App\Models\Admin\Slider;
use App\Models\Admin\About;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;


class PageController extends Controller
{
    public function index()
    {
        $pages = Page::get();

        return view('backend.website_settings.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('backend.website_settings.pages.create');
    }

    public function store(Request $request)
    {
        $page = new Page;
        $page->title = $request->title;

        $slug = $request->slug ? $request->slug : $request->title;
        $page->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($slug)));

        $page->type = "custom_page";
        $page->content = $request->content;
        $page->meta_title = $request->meta_title;
        $page->meta_description = $request->meta_description;
        $page->keywords = $request->keywords;
        $page->meta_image = $request->meta_image;

        if (Page::where('slug', $page->slug)->first() == null) {
            $page->save();

            flash(translate('New page has been created successfully'))->success();
            return redirect()->route('website.pages');
        }

        flash(translate('Slug has been used already'))->warning();
        return back();
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        $page = Page::where('slug', $id)->firstOrFail();

        $discovers = Discover::all();
        $sliders = Slider::all();
        $blogs = Blog::all();
        $categories = Category::all();

        return view('backend.website_settings.pages.edit', compact('page', 'discovers', 'sliders', 'blogs', 'categories'));
    }

    public function aboutEditPage(Request $request, $id = 'about-us')
    {
        $page = Page::where('slug', $id)->firstOrFail();
        $sections = About::all();
        $counterItems = BusinessSetting::where('type', 'counter_items')->first();
        return view('backend.website_settings.pages.about_page', compact('page', 'sections', 'counterItems'));
    }

    public function update_section(Request $request, $id)
    {
        $page = Page::where('slug', $id)->firstOrFail();
        $slug = $page->slug;

        $data = $request->except(['_token', '_method']);

        $sectionMap = [
            'slider' => 'sliders',
            'discover' => 'discovers',
            'blog' => 'blogs',
            'dropshipper_review' => 'dropshipper_reviews',
            'dropshipper_categories' => 'dropshipper_categoriess',
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

    public function update(Request $request, $id)
    {
        $page = Page::where('slug', $id)->firstOrFail();

        if ($request->has('slug')) {
            $slug = $request->slug ? $request->slug : $request->title;
            $slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($slug)));

            $existingPage = Page::where('slug', $slug)->first();
            if ($existingPage && $existingPage->id != $page->id) {
                flash(translate('Slug has been used already'))->warning();
                return back();
            }

            if ($page->type == 'custom_page') {
                $page->slug = $slug;
            }
        }

        $page->title = $request->title;
        $page->content = $request->content;
        $page->meta_title = $request->meta_title;
        $page->meta_description = $request->meta_description;
        $page->keywords = $request->keywords;
        $page->meta_image = $request->meta_image;

        $page->save();

        flash(translate('Page has been updated successfully'))->success();
        return redirect()->route('website.pages');
    }

    public function show_custom_page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if ($page->slug == 'home') {
            return redirect()->route('admin.dashboard');
        }
        if ($page != null) {
            return view('frontend.custom_page', compact('page'));
        }
        abort(404);
    }
    public function mobile_custom_page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if ($page != null) {
            return view('frontend.m_custom_page', compact('page'));
        }
        abort(404);
    }
}
