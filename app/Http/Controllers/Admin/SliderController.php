<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Admin\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest('id')->paginate(10);

        return view('backend.slider.index', compact('sliders'));
    }

    public function create()
    {
        return view('backend.slider.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'photos'          => 'nullable',
            'title'           => 'required|string|max:255',
            'sub_title'       => 'required|string|max:255',
            'button_name'     => 'required|string|max:255',
            'button_link'     => 'required|url'
        ]);

        $slider = new Slider();

        $slider->title = $request->input('title');
        $slider->sub_title = $request->input('sub_title');
        $slider->button_name = $request->input('button_name');
        $slider->button_link = $request->input('button_link');
        $slider->photos = $request->input('photos');

        $slider->save();

        flash(translate('Slider created successfully.'))->success();

        return redirect()->route('slider.index');
    }


    public function edit(Slider $slider)
    {
        return view('backend.slider.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        // Validation
        $request->validate([
            'photos' => 'nullable',
            'title' => 'required',
            'sub_title' => 'required',
            'button_name' => 'required',
            'button_link' => 'required',
        ]);

        // Update Slider
        $slider->title = $request->title;
        $slider->sub_title = $request->sub_title;
        $slider->button_name = $request->button_name;
        $slider->button_link = $request->button_link;
        $slider->photos = $request->photos;

        $slider->save();

        flash(translate('Slider updated successfully.'))->success();
        return redirect()->route('slider.index');
    }


    public function destroy($id)
    {
        try {
            $slider = Slider::findOrFail($id);
            $slider->delete();
            flash(translate('Slider deleted successfully.'))->success();
            return redirect()->route('slider.index');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
