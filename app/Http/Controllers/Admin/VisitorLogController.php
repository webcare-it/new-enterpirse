<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\VisitorLog;
use App\Http\Controllers\Controller;

class VisitorLogController extends Controller
{
    public function index()
    {
        $logs = VisitorLog::latest()->paginate(15);
        return view('backend.visitor.index', compact('logs'));
    }

    public function destroy($id)
    {
        $log = VisitorLog::findOrFail($id);
        $log->delete();

        flash(translate('Visitor log has been deleted successfully'))->success();
        return back();
    }

    public function block($id)
    {
        $log = VisitorLog::findOrFail($id);
        $log->is_blocked = 1;
        $log->save();

        flash(translate('Visitor IP has been blocked successfully'))->success();
        return back();
    }

    public function unblock($id)
    {
        $log = VisitorLog::findOrFail($id);
        $log->is_blocked = 0;
        $log->save();

        flash(translate('Visitor IP has been unblocked successfully'))->success();
        return back();
    }
}
