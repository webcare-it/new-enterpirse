<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Blog;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('backend.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('backend.blog.create');
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'blog_title' => 'required',
            'thumbnail' => 'nullable',
            'main_image' => 'nullable',
            'short_description' => 'required',
            'long_description' => 'required',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_img' => 'nullable',
        ]);

        // Create new Blog
        $blog = new Blog();

        $blog->blog_title = $request->blog_title;
        $blog->short_description = $request->short_description;
        $blog->long_description = $request->long_description;
        $blog->thumbnail = $request->thumbnail;
        $blog->main_image = $request->main_image;


        // Slug generate
        $slug = Str::slug($request->blog_title);
        if (Blog::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }
        $blog->slug = $slug;


        // Tags handling (clean trim)
        if ($request->tags) {
            $blog->tags = implode(',', array_map('trim', explode(',', $request->tags)));
        }

        // User assign
        $blog->user_id = Auth::id();

        // Meta fields
        $blog->meta_title = $request->meta_title ?? $request->blog_title;
        $blog->meta_description = $request->meta_description ?? strip_tags($request->short_description);
        $blog->meta_image = $request->meta_img;


        $blog->save();

        flash(translate('Blog created successfully.'))->success();
        return redirect()->route('blog.index');
    }

    public function edit(Blog $blog)
    {
        return view('backend.blog.edit', compact('blog'));
    }



    public function update(Request $request, Blog $blog)
    {
        // Validation
        $request->validate([
            'blog_title' => 'required',
            'thumbnail' => 'nullable',
            'main_image' => 'nullable',
            'short_description' => 'required',
            'long_description' => 'required',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_img' => 'nullable',
        ]);

        // Thumbnail
        $blog->thumbnail = $request->thumbnail;
        // Main Image
        $blog->main_image = $request->main_image;
        // Meta Image
        $blog->meta_image = $request->meta_img;



        $blog->blog_title = $request->blog_title;
        $blog->short_description = $request->short_description;
        $blog->long_description = $request->long_description;

        // ================= SLUG =================

        if ($blog->blog_title !== $request->blog_title) {
            $slug = Str::slug($request->blog_title);

            if (Blog::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
                $slug = $slug . '-' . time();
            }

            $blog->slug = $slug;
        }


        // Tags update (clean format)
        if ($request->tags) {
            $blog->tags = implode(',', array_map('trim', explode(',', $request->tags)));
        } else {
            $blog->tags = null;
        }

        $blog->meta_title = $request->meta_title ?? $request->blog_title;
        $blog->meta_description = $request->meta_description ?? strip_tags($request->short_description);


        $blog->save();

        flash(translate('Blog updated successfully.'))->success();
        return redirect()->route('blog.index');
    }


    public function show(Blog $blog)
    {
        return view('backend.blog.show', compact('blog'));
    }


    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        flash(translate('Blog deleted successfully.'))->success();
        return redirect()->route('blog.index');
    }
}
