<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVarient;
use App\Models\OrderDetail;
use App\Models\Vendor\VendorOrderProduct;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Utility\SteadfastUtility;
use App\Utility\PathaoUtility;
use Log;
use Validator;

class CourierController extends Controller
{
    public function reset_courier($id)
    {
        $order = Order::findOrFail($id);
        $order->courier_name = null;
        $order->consignment_id = null;
        $order->courier_tracking_code = null;
        $order->tracking_code = null;
        $order->save();

        flash(translate('Courier assignment cleared. You can select another courier.'))->success();

        return redirect()->route('orders.show', $order->id);
    }

    public function send_to_steadfast(Request $request, $id)
    {
        $order = Order::with('details')->findOrFail($id);

        if ($order->courier_name) {
            return response()->json([
                'success' => false,
                'message' => translate('A courier has already been assigned to this order.')
            ], 422);
        }

        $shipping_address = $order->shipping_address;

        $subtotal = $order->orderDetails->sum(function ($detail) {
            return $detail->price * $detail->quantity;
        });

        $grand_total = $subtotal + $order->shipping_cost;


        if (!$shipping_address) {
            return response()->json([
                'success' => false,
                'message' => translate('Invalid shipping address data')
            ]);
        }

        $phone = $order->phone_number ?? '';
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) > 11) {
            $phone = substr($phone, -11);
        }

        $cod_amount = $grand_total;

        if ($order->payment_status == 'paid') {
            $cod_amount = 0;
        }

        $item_description = $order->orderDetails->map(function ($detail) {
            return ($detail->product->name ?? '') . ' x ' . $detail->quantity;
        })->implode(', ');

        $payload = [
            'invoice'           => $order->code,
            'recipient_name'    => substr($order->name ?? '', 0, 100),
            'recipient_phone'   => $phone,
            'recipient_address' => substr($order->shipping_address ?? '', 0, 100),
            'cod_amount'        => $cod_amount,
            'note'              => $order->notes ?? '',
            'item_description'  => substr($item_description, 0, 250),
        ];

        $response = SteadfastUtility::create_order($payload);

        if (isset($response['status']) && $response['status'] == 200) {
            $order->courier_name = 'steadfast';
            $order->courier_tracking_code = $response['consignment']['tracking_code'] ?? null;
            $order->consignment_id = $response['consignment']['tracking_code'] ?? null;
            $order->delivery_status = 'on_the_way';
            $order->tracking_code = $response['consignment']['tracking_code'] ?? null;
            $order->save();

            flash(translate('Courier updated successfully!'))->success();
            return redirect()->route('orders.show', $order->id);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response['message'] ?? translate('Something went wrong with Steadfast API.')
            ]);
        }
    }

    public function pathao_stores()
    {
        $response = PathaoUtility::get_stores();

        if (isset($response['data']['data'])) {
            return response()->json([
                'success' => true,
                'stores' => $response['data']['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response['message'] ?? translate('Unable to fetch Pathao stores.')
        ], $response['status'] ?? 502);
    }

    public function send_to_pathao(Request $request, $id)
    {
        $order = Order::with('details')->findOrFail($id);

        if ($order->courier_name) {
            return response()->json([
                'success' => false,
                'message' => translate('A courier has already been assigned to this order.')
            ], 422);
        }

        $validated = $request->validate([
            'store_id' => ['required', 'integer', 'min:1'],
            'delivery_type' => ['required', 'integer', 'in:12,48'],
            'item_type' => ['required', 'integer', 'in:1,2'],
            'item_weight' => ['required', 'numeric', 'min:0.5', 'max:10'],
        ]);

        $shipping_address = $order->shipping_address;

        $subtotal = $order->orderDetails->sum(function ($detail) {
            return $detail->price * $detail->quantity;
        });

        $grand_total = $subtotal + $order->shipping_cost;


        if (!$shipping_address) {
            return response()->json([
                'success' => false,
                'message' => translate('Invalid shipping address data')
            ]);
        }

        $phone = $order->phone_number ?? '';
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) > 11) {
            $phone = substr($phone, -11);
        }

        $cod_amount = $grand_total;

        if ($order->payment_status == 'paid') {
            $cod_amount = 0;
        }

        $item_description = $order->orderDetails->map(function ($detail) {
            return ($detail->product->name ?? '') . ' x ' . $detail->quantity;
        })->implode(', ');

        $item_quantity = (int) $order->orderDetails->sum('quantity');

        $payload = [
            'merchant_order_id'   => $order->code,
            'recipient_name'      => substr($order->name ?? '', 0, 100),
            'recipient_phone'     => $phone,
            'recipient_address'   => substr($order->shipping_address ?? '', 0, 100),
            'delivery_type'       => $validated['delivery_type'],
            'store_id'            => $validated['store_id'],
            'item_type'           => $validated['item_type'],
            'item_weight'         => (string) $validated['item_weight'],
            'special_instruction' => $order->notes ?? '',
            'item_quantity'       => (int) $item_quantity,
            'item_description'    => substr($item_description, 0, 250),
            'amount_to_collect'   => (int) round($cod_amount),
        ];

        $response = PathaoUtility::create_order($payload);

        $consignment_id = $response['data']['consignment_id'] ?? ($response['consignment_id'] ?? null);
        $order_code     = $response['data']['order_code'] ?? ($response['order_code'] ?? null);

        if ($consignment_id) {
            $order->courier_name = 'pathao';
            $order->courier_tracking_code = $order_code ?: $consignment_id;
            $order->delivery_status = 'on_the_way';
            $order->tracking_code   = $order_code ?: $consignment_id;
            $order->consignment_id  = $consignment_id;
            $order->save();

            flash(translate('Order has been sent to Pathao Courier successfully.'))->success();

            return redirect()->route('orders.show', $order->id);
        } else {
            $message = $response['message'] ?? translate('Something went wrong with Pathao API.');
            if (!empty($response['errors']) && is_array($response['errors'])) {
                $message .= ' ' . collect($response['errors'])->flatten()->implode(' ');
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
    }


}
