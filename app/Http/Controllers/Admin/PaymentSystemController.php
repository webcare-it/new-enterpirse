<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSystem;
use Illuminate\Http\Request;

class PaymentSystemController extends Controller
{
    /**
     * Display a listing of payment systems.
     */
    public function index()
    {
        $paymentSystems = PaymentSystem::orderBy('id', 'desc')->paginate(10);
        return view('backend.payment_system.index', compact('paymentSystems'));
    }

    /**
     * Show the form for creating a new payment system.
     */
    public function create()
    {
        return view('backend.payment_system.create');
    }

    /**
     * Store a newly created payment system.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|string|max:100|unique:payment_systems,type',
            'image'      => 'required',
            'is_default' => 'nullable|boolean',
        ]);

        // If this is set as default, unset any previous default
        if ($request->is_default) {
            PaymentSystem::where('is_default', 1)->update(['is_default' => 0]);
        }

        $paymentSystem = new PaymentSystem();
        $paymentSystem->title = $request->title;
        $paymentSystem->type = $request->type;
        $paymentSystem->image = $request->image;
        $paymentSystem->is_default = $request->is_default ?? 0;
        $paymentSystem->save();

        flash(translate('Payment system created successfully.'))->success();
        return redirect()->route('paymentsystem.index');
    }

    /**
     * Show the form for editing the specified payment system.
     */
    public function edit($id)
    {
        $paymentSystem = PaymentSystem::findOrFail($id);
        return view('backend.payment_system.edit', compact('paymentSystem'));
    }

    /**
     * Update the specified payment system.
     */
    public function update(Request $request, $id)
    {
        $paymentSystem = PaymentSystem::findOrFail($id);

        $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|string|max:100|unique:payment_systems,type,' . $paymentSystem->id,
            'image'      => 'nullable',
            'is_default' => 'nullable|boolean',
        ]);

        // If this is set as default, unset any previous default
        if ($request->is_default) {
            PaymentSystem::where('is_default', 1)->where('id', '!=', $paymentSystem->id)->update(['is_default' => 0]);
        }

        $paymentSystem->title = $request->title;
        $paymentSystem->type = $request->type;
        $paymentSystem->is_default = $request->is_default ?? 0;

        if ($request->image) {
            $paymentSystem->image = $request->image;
        }

        $paymentSystem->save();

        flash(translate('Payment system updated successfully.'))->success();
        return redirect()->route('paymentsystem.index');
    }

    /**
     * Remove the specified payment system.
     */
    public function destroy($id)
    {
        $paymentSystem = PaymentSystem::findOrFail($id);
        $paymentSystem->delete();

        flash(translate('Payment system deleted successfully.'))->success();
        return redirect()->route('paymentsystem.index');
    }
}
