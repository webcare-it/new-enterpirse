<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $newsletters = Newsletter::latest('id')->paginate(10);
        return view('backend.newsletter.newsletter', compact('newsletters'));
    }

    public function updateStatus(Request $request)
    {
        try {
            $item = \App\Models\Admin\Newsletter::findOrFail($request->id);
            $item->status = $request->status;
            $item->save();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function destroy($id)
    {
        $newsletter = \App\Models\Admin\Newsletter::findOrFail($id);
        $newsletter->delete();

        flash(translate('Newsletter deleted successfully.'))->success();
        return redirect()->route('newsletter.index');
    }
}
