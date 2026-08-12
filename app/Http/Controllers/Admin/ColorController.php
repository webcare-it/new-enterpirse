<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        $sort_search = null;
        $colors = Color::orderBy('created_at', 'desc');

        if ($request->search != null) {
            $colors = $colors->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        $colors = $colors->paginate(10);

        return view('backend.color.index', compact('colors', 'sort_search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:colors|max:255',
        ]);
        $color = new Color;
        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;

        $color->save();

        flash(translate('Color has been inserted successfully'))->success();
        return redirect()->route('colors.index');
    }

    public function edit(Color $color)
    {
        return view('backend.color.edit', compact('color'));
    }

    /**
     * Update the color.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Color $color)
    {
        $request->validate([
            'code' => 'required|unique:colors,code,' . $color->id,
        ]);

        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;

        $color->save();

        flash(translate('Color has been updated successfully'))->success();
        return back();
    }

    public function destroy($id)
    {
        // Color::destroy($id);
        flash(translate('Color has been deleted successfully'))->success();
        return redirect()->route('colors.index');
    }
}
