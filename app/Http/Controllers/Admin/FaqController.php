<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = \App\Models\Admin\Faq::oldest('position')->paginate(10);
        return view('backend.faq.index', compact('faqs'));
    }

    public function create()
    {
        return view('backend.faq.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required'
        ]);

        $faq = new \App\Models\Admin\Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->status = 1;
        $faq->save();

        flash(translate('FAQ created successfully.'))->success();
        return redirect()->route('faq.index');
    }

    public function edit(\App\Models\Admin\Faq $faq)
    {
        return view('backend.faq.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question'  => 'required',
            'answer'  => 'required',
            'status' => 'nullable|boolean'
        ]);
        $faq = \App\Models\Admin\Faq::findOrFail($id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        // checkbox fix (if unchecked → 0)
        if (!$request->has('status')) {
            $faq->status = 0;
        } else {
            $faq->status = 1;
        }

        $faq->save();

        flash(translate('FAQ updated successfully.'))->success();
        return redirect()->route('faq.index');
    }

    public function destroy($id)
    {
        $faq = \App\Models\Admin\Faq::findOrFail($id);
        $faq->delete();

        flash(translate('FAQ deleted successfully.'))->success();
        return redirect()->route('faq.index');
    }

    public function sort(Request $request)
    {
        foreach ($request->positions as $item) {
            \App\Models\Admin\Faq::where('id', $item['id'])
                ->update(['position' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }
}
