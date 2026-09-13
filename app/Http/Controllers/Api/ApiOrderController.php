<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cart;
use App\Models\Admin\IncompleteOrder;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\OrderDetail;
use App\Models\ShippingCost;
use App\Models\User;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\SmsService;

class ApiOrderController extends Controller
{
    /**
     * Place an order from the current cart.
     * $tempUserId = session()->get('temp_user_id');
     */
    public function place(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required|string',
            'payment_type'     => 'required|in:cod,card,bkash,nagad,rocket',
            'notes'            => 'nullable|string',
            'shipping_type'    => 'sometimes|in:flat_rate,free,pickup',
            'shipping_area'    => 'required|exists:shipping_costs,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $incomingTempId = $request->header('User-Id');
        $userId = null;
        $tempUserId = null;

        $createUserFromRequest = function () use ($request) {
            if ($request->filled('name') && $request->filled('email') && $request->filled('phone')) {
                $existing = User::where('email', $request->email)->first();
                if ($existing) {
                    return $existing->id;
                }
                $user = User::create([
                    'name'  => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);
                return $user->id;
            }
            return null;
        };

        if ($incomingTempId) {
            $user = User::where('id', $incomingTempId)->first();
            if ($user) {
                $userId = $user->id;
            } else {
                $tempUserId = $incomingTempId;
                $createdId = $createUserFromRequest();
                if ($createdId) {
                    $userId = $createdId;
                }
            }
        } else {
            $createdId = $createUserFromRequest();
            if ($createdId) {
                $userId = $createdId;
            } else {
                $tempUserId = (string) Str::uuid();
            }
        }

        $isorder = Order::where('user_id', $userId)->orWhere('guest_id', $tempUserId)->get();

        if ($isorder->isEmpty()) {
            $customer_type = 'new';
        } else {
            $customer_type = 'returning';
        }

        $cartItems = Cart::query()
            ->where(function ($query) use ($userId, $tempUserId) {
                if ($userId) {
                    $query->orWhere('user_id', $userId);
                }
                if ($tempUserId) {
                    $query->orWhere('temp_user_id', $tempUserId);
                }
            })
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
        }

        $shippingArea = ShippingCost::find($request->shipping_area);
        if (!$shippingArea) {
            return response()->json(['success' => false, 'message' => 'Invalid shipping area'], 400);
        }

        // Calculate totals
        $subtotal = 0;
        $taxTotal = 0;
        $discountTotal = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item->price * $item->quantity;
            $taxTotal += $item->tax * $item->quantity;
            $discountTotal += $item->discount * $item->quantity;
        }

        $shippingCost = $shippingArea->amount;
        if ($request->shipping_type === 'free' || $request->shipping_type === 'pickup') {
            $shippingCost = 0;
        }

        $couponDiscount = $cartItems->sum('coupon_discount');
        $couponCode = $cartItems->first()?->coupon_code;
        $finalTotal = $subtotal + $taxTotal - $discountTotal + $shippingCost - $couponDiscount;

        $orderCode = 'ORD-' . strtoupper(Str::random(8)) . '-' . time();

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id'         => $userId,
                'guest_id'        => $userId ? null : $tempUserId,
                'shipping_address' => $request->shipping_address,
                'shipping_type'    => $request->shipping_type ?? 'flat_rate',
                'shipping_cost'    => $shippingArea->amount,
                'shipping_area_id' => $shippingArea->id,
                'coupon_discount'  => $couponDiscount,
                'discount'         => $discountTotal,
                'grand_total'      => $finalTotal,
                'code'             => $orderCode,
                'notes'            => $request->notes,
                'name'             => $request->name,
                'email_address'    => $request->email,
                'phone_number'     => $request->phone,
                'payment_type'     => $request->payment_type,
                'payment_status'   => 'unpaid',
                'delivery_status'  => 'pending',
                'date'             => now(),
                'viewed'           => 0,
                'delivery_viewed'  => 0,
                'payment_status_viewed' => 0,
                'commission_calculated' => 0,
                'order_type'       => 'normal'
            ]);

            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id'            => $order->id,
                    'seller_id'           => $item->owner_id ?? null,
                    'product_id'          => $item->product_id,
                    'sku'                 => $item->sku,
                    'variation'           => $item->variation,
                    'price'               => $item->price,
                    'tax'                 => $item->tax,
                    'shipping_cost'       => 0,
                    'quantity'            => $item->quantity,
                    'payment_status'      => 'unpaid',
                    'delivery_status'     => 'pending',
                    'shipping_type'       => $item->shipping_type,
                    'product_referral_code' => $item->product_referral_code,
                ]);
            }

            $cartItems->each->delete();

            DB::commit();

            // Load order details with product
            $order->load('details.product');

            // Check if OTP for order is enabled
            $otpForOrder = get_setting('otp_for_order') == 1;
            $otpCode = null;

            if ($otpForOrder) {
                $otpCode = rand(100000, 999999);
                $order->is_otp_verified = $otpCode;
                $order->save();

                // Send OTP to customer
                $smsTemplate = \App\Models\SmsTemplate::where('identifier', 'order_otp')->first();
                if ($smsTemplate) {
                    $smsBody = str_replace('[[code]]', $otpCode, $smsTemplate->sms_body);
                    $smsBody = str_replace('[[site_name]]', env('APP_NAME', 'Enterprise'), $smsBody);
                    try {
                        sendSMS($order->phone_number, env('APP_NAME'), $smsBody, $smsTemplate->template_id);
                    } catch (\Exception $e) {
                        \Log::error('SMS order_otp failed: ' . $e->getMessage());
                    }
                }
            }



            // If OTP for order is enabled, return simple response
            if ($otpForOrder) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order placed. Please verify OTP.',
                    'data' => [
                        'otp_sent'   => true,
                        'order_code' => $order->code,
                    ],
                ]);
            }

             // Send order receive SMS to admin
            if (get_setting('is_order_receive') == 1) {
                SmsService::order_receive($order);
            }

            // Build items with required fields
            $items = $order->details->map(function ($detail) {
                $product = $detail->product;
                return [
                    'id' => $detail->id,
                    'product' => $product ? [
                        'id'        => $product->id,
                        'name'      => $product->name,
                        'slug'      => $product->slug,
                        'price'     => (float) $detail->price, // price from detail (may differ from product price)
                        'image'     => (string) (uploaded_asset($product->thumbnail) ?? ''),
                        'quantity'  => (int) $detail->quantity,
                        'variation' => json_decode($detail->variation, true) ?? null,
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data' => [
                    'summary' => [
                        'subtotal'        => $subtotal,
                        'shipping_cost'   => (int) $shippingCost,
                        'coupon_discount' => $couponDiscount,
                        'coupon_code'     => $couponCode,
                        'discount'        => $discountTotal,
                        'tax'             => $taxTotal,
                        'grand_total'     => $finalTotal,
                    ],
                    'customer' => [
                        'user_id' => $order->user_id,
                        "customer_type" => $customer_type,
                        'name'    => $order->name,
                        'email'   => $order->email_address,
                        'phone'   => $order->phone_number,
                        'address' => $order->shipping_address,
                    ],
                    'date'  => $order->date,
                    'code'  => $order->code,
                    'id'    => $order->id,
                    'items' => $items,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * List orders for authenticated user (or guest).
     */
    public function index(Request $request)
    {
        $requestedUserId = $request->header('User-Id');
        $userId = null;
        $tempUserId = null;

        if ($requestedUserId) {
            $user = User::where('id', $requestedUserId)->first();
            if ($user) {
                $userId = $user->id;
            } else {
                $tempUserId = $requestedUserId;
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User-Id header is required'
            ], 401);
        }

        $orders = Order::with(['details.product'])
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when(!$userId && $tempUserId, function ($query) use ($tempUserId) {
                return $query->where('guest_id', $tempUserId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15); // Use pagination instead of get()

        return response()->json([
            'success' => true,
            'data' => $orders->items(), // or transform via resource
            'pagination' => [
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
            ]
        ]);
    }

    /**
     * Get a specific order with details.
     */
    public function show(Request $request, $code)
    {
        $requestedUserId = $request->header('User-Id');
        if (!$requestedUserId) {
            return response()->json([
                'success' => false,
                'message' => 'User-Id header is required'
            ], 401);
        }

        $user = User::where('id', $requestedUserId)->first();
        $userId = $user ? $user->id : null;
        $guestId = $user ? null : $requestedUserId;

        $order = Order::with('details.product')
            ->where('code', $code)
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($guestId, function ($query) use ($guestId) {
                return $query->where('guest_id', $guestId);
            })
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $paymentTitles = [
            'cod'    => 'Cash on Delivery',
            'paypal' => 'PayPal',
            'stripe' => 'Credit Card (Stripe)',
            'bkash'  => 'bKash',
            'nagad'  => 'Nagad',
        ];

        $statusOrder = ['pending', 'confirmed', 'picked_up', 'on_the_way', 'delivered'];
        $statusLabels = [
            'pending'    => 'Order Placed',
            'confirmed'  => 'Confirmed',
            'picked_up'  => 'Picked Up',
            'on_the_way' => 'On The Way',
            'delivered'  => 'Delivered',
            'cancelled'  => 'Cancelled',
        ];

        $currentStatus = $order->delivery_status;
        $steps = [];

        if ($currentStatus === 'cancelled') {
            foreach ($statusOrder as $status) {
                $steps[] = [
                    'status'    => $status,
                    'label'     => $statusLabels[$status],
                    'completed' => false,
                ];
            }
            $steps[] = [
                'status'    => 'cancelled',
                'label'     => $statusLabels['cancelled'],
                'completed' => true,
            ];
        } else {
            $currentIndex = array_search($currentStatus, $statusOrder);
            foreach ($statusOrder as $index => $status) {
                $steps[] = [
                    'status'    => $status,
                    'label'     => $statusLabels[$status],
                    'completed' => $index <= $currentIndex,
                ];
            }
            $steps[] = [
                'status'    => 'cancelled',
                'label'     => $statusLabels['cancelled'],
                'completed' => false,
            ];
        }

        $userId = $order->user_id;

        $user = User::find($userId);

        $shippingName = $user->name ?? null;
        $shippingEmail = $user->email ?? null;
        $shippingPhone = $user->phone ?? null;
        $shippingAddress = $order->shipping_address ?? null;

        // If separate fields are missing, try to parse shipping_address as JSON shipping_cost
        if (!$shippingName && !$shippingEmail && !$shippingPhone && $shippingAddress) {
            $decoded = json_decode($shippingAddress, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $shippingName = $decoded['name'] ?? $decoded['full_name'] ?? null;
                $shippingEmail = $decoded['email'] ?? null;
                $shippingPhone = $decoded['phone'] ?? $decoded['mobile'] ?? null;
            }
        }


        if ($user) {
            $shippingName = $shippingName ?? $user->name;
            $shippingEmail = $shippingEmail ?? $order->email_address;
            $shippingPhone = $shippingPhone ?? $order->phone_number;
        }

        $products = $order->details->map(function ($detail) {
            $product = $detail->product;
            if (!$product) {
                return null;
            }

            $thumbnail = null;
            if ($product->thumbnail) {
                $decoded = json_decode($product->thumbnail, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                    $thumbnail = uploaded_asset($decoded[0]);
                } else {
                    $thumbnail = uploaded_asset($product->thumbnail);
                }
            }

            if (!$thumbnail && $product->photos) {
                $photos = json_decode($product->photos, true);
                if (is_array($photos) && !empty($photos)) {
                    $thumbnail = uploaded_asset($photos[0]);
                }
            }

            $variation = $detail->variation;
            if (is_string($variation) && str_starts_with($variation, '[') || str_starts_with($variation, '{')) {
                $variation = json_decode($variation, true) ?? $variation;
            }

            return [
                'id'        => $product->id,
                'name'      => $product->name,
                'slug'      => $product->slug,
                'price'     => (float) $detail->price,
                'quantity'  => (int) $detail->quantity,
                'image'     => $thumbnail,
                'variation' => $variation,
            ];
        })->filter();

        $structuredData = [
            'id'                  => $order->id,
            'code'                => $order->code,
            'date'                => $order->created_at->toDateString(),
            'status'              => $currentStatus,
            'payment_status'      => $order->payment_status,
            'payment_method'      => $order->payment_type,
            'payment_method_title' => $paymentTitles[$order->payment_type] ?? ucfirst($order->payment_type),

            'shipping' => [
                'name'    => $order->name,
                'email'   => $order->email_address,
                'phone'   => $order->phone_number,
                'address' => $shippingAddress,
                'notes'   => $order->notes ?? null,
            ],

            'summary' => [
                'subtotal'         => (float) $order->details->sum(fn($d) => $d->price * $d->quantity),
                'discount'         => (float) ($order->discount ?? 0),
                'tax'              => (float) $order->details->sum('tax'),
                'shipping_cost'    => (int) $order->shipping_cost,
                'coupon_discount'  => (float) ($order->coupon_discount ?? 0),
                'total'            => (float) $order->grand_total,
            ],

            'products' => $products,
            'steps'    => $steps,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Order found',
            'data'    => ['details' => $structuredData],
        ]);
    }

    private function getCartItems($userId, $tempUserId)
    {
        $query = Cart::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($tempUserId) {
            $query->where('temp_user_id', $tempUserId);
        } else {
            return collect();
        }
        return $query->get();
    }

    /**
     * Get a specific order with details.
     */
    public function orderTracking($code)
    {
        $order = Order::with('details.product')
            ->where('code', $code)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $details = $order->details->map(function ($detail) {
            $product = $detail->product;
            return [
                'id'              => $detail->id,
                'order_id'        => $detail->order_id,
                'seller_id'       => $detail->seller_id,
                'product_id'      => $detail->product_id,
                'variation'       => json_decode($detail->variation, true) ?? $detail->variation,
                'price'           => (float) $detail->price,
                'tax'             => (float) $detail->tax,
                'shipping_cost'   => (float) $detail->shipping_cost,
                'quantity'        => (int) $detail->quantity,
                'payment_status'  => $detail->payment_status,
                'delivery_status' => $detail->delivery_status,
                'shipping_type'   => $detail->shipping_type,
                'created_at'      => $detail->created_at,
                'updated_at'      => $detail->updated_at,
                'product' => $product ? [
                    'id'             => $product->id,
                    'name'           => $product->name,
                    'slug'           => $product->slug,
                    'thumbnail'  => $product->thumbnail ? uploaded_asset($product->thumbnail) : null,
                    'photos'         => $product->photos ? array_map('uploaded_asset', json_decode($product->photos, true)) : [],
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Order found',
            'data' => [
                'order_code'      => $order->code,
                'tracking_code'   => $order->tracking_code,
                'delivery_status' => $order->delivery_status,
                'payment_status'  => $order->payment_status,
                'payment_type'    => $order->payment_type,
                'shipping_type'   => $order->shipping_type,
                'grand_total'     => (float) $order->grand_total,
                'shipping_cost'   => (float) $order->shipping_cost,
                'created_at'      => $order->created_at,
                'details'         => $details,
            ]
        ]);
    }


    public function incompleteOrder(Request $request)
    {
        if (get_setting('is_active_in_co_oder') != 1) {
            return response()->json(['success' => true]);
        }

        $requestedUserId = $request->header('User-Id');
        $user = User::find($requestedUserId);

        if ($user) {
            $cartItems = Cart::where('user_id', $user->id)->get();
            $identifier = ['user_id' => $user->id];
        } else {
            $cartItems = Cart::where('temp_user_id', $requestedUserId)->get();
            $identifier = ['temp_user_id' => $requestedUserId];
        }

        $orderCode = 'INC-ORD-' . strtoupper(uniqid());

        $incompleteOrder = IncompleteOrder::firstOrCreate(
            $identifier,
            [
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                'customer_email' => $request->email,
                'shipping' => $request->shipping,
                'notes' => $request->notes,
                'order_code' => $orderCode,
                'status' => 'pending',
            ]
        );

        $formattedCartData = $cartItems->map(function ($cart) {
            $product = Product::find($cart->product_id);
            return [
                'sku'           => $product->sku ?? null,
                'name'          => $product->name ?? 'Unknown',
                'price'         => $cart->price,
                'stock'         => $product->stock ?? 0,
                'total'         => $cart->price * $cart->quantity,
                'quantity'      => $cart->quantity,
                'thumbnail'     => $product->thumbnail ?? null,
                'variation'     => $cart->variation ?? null,
                'attributes'    => json_decode($cart->variation, true) ?? [],
                'product_id'    => $cart->product_id,
                'variant_id'    => null,
                'regular_price' => $product->regular_price ?? $cart->price,
            ];
        })->toArray();

        $incompleteOrder->cart_data = $formattedCartData;
        $incompleteOrder->subtotal = $cartItems->sum(fn($c) => $c->price * $c->quantity);
        $incompleteOrder->total = $incompleteOrder->subtotal;

        if ($user) {
            $incompleteOrder->customer_name = $user->name;
            $incompleteOrder->customer_email = $user->email;
            $incompleteOrder->customer_phone = $user->phone ?? null;
        }

        $incompleteOrder->save();

        return response()->json([
            'success' => true
        ]);
    }


    public function invoice($id)
    {
        try {
            $order = Order::with(['orderDetails', 'orderDetails.product', 'user'])->findOrFail($id);
            $customer = $order->user;
            $subtotal = $order->orderDetails->sum(function ($detail) {
                return $detail->price * $detail->quantity;
            });
            $totalTax = $order->orderDetails->sum('tax');
            $totalShipping = $order->orderDetails->sum('shipping_cost');


            $pdf = Pdf::loadView('backend.sales.all_orders.invoice-pdf', compact(
                'order',
                'customer',
                'subtotal',
                'totalTax',
                'totalShipping'
            ));

            $pdf->setPaper('A4', 'portrait');

            return $pdf->download('invoice-' . $order->code . '.pdf');
        } catch (\Exception $e) {
            // Log error and redirect back with error message
            \Log::error('Invoice download error: ' . $e->getMessage());
            flash(translate('Failed to generate invoice. Please try again.'))->error();
            return back();
        }
    }


    private function generateOrderCode()
    {
        return 'INC-ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
    }

    /**
     * Verify order OTP.
     */
    public function verifyOrderOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_code' => 'required|string|exists:orders,code',
            'otp'        => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $order = Order::where('code', $request->order_code)->first();

        if ($order->is_otp_verified != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP code.',
            ], 422);
        }

        $order->is_otp_verified = 'verified';
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order verified successfully.',
        ]);
    }

    /**
     * Resend order OTP.
     */
    public function resendOrderOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_code' => 'required|string|exists:orders,code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $order = Order::where('code', $request->order_code)->first();
        $otpCode = rand(100000, 999999);
        $order->is_otp_verified = $otpCode;
        $order->save();

        $smsTemplate = \App\Models\SmsTemplate::where('identifier', 'order_otp')->first();
        if ($smsTemplate) {
            $smsBody = str_replace('[[code]]', $otpCode, $smsTemplate->sms_body);
            $smsBody = str_replace('[[site_name]]', env('APP_NAME', 'Enterprise'), $smsBody);
            try {
                sendSMS($order->phone_number, env('APP_NAME'), $smsBody, $smsTemplate->template_id);
            } catch (\Exception $e) {
                \Log::error('SMS order_otp failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP resent successfully.',
        ]);
    }
}
