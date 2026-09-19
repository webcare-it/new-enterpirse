<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FraudCheckerController extends Controller
{
    public function index(Request $request)
    {
        $phone = $request->query('phone');

        if (! $phone) {
            $queryString = $request->getQueryString();
            if ($queryString && ! str_contains($queryString, '=')) {
                $phone = $queryString;
            }
        }

        if ($phone) {
            $phone = strtr((string) $phone, [
                '০' => '0', '১' => '1', '২' => '2', '৩' => '3', '৪' => '4',
                '৫' => '5', '৬' => '6', '৭' => '7', '৮' => '8', '৯' => '9',
            ]);

            $phone = preg_replace('/\D+/', '', $phone);

            if (strlen($phone) > 11 && str_starts_with($phone, '880') && strlen($phone) === 13) {
                $phone = substr($phone, 2);
            }
        }

        $result = null;
        $error = null;
        $matchedOrders = collect();

        if ($phone) {
            $matchedOrders = Order::where('phone_number', $phone)
                ->orderBy('created_at', 'desc')
                ->get();

            $apiKey = get_setting('fraud_checker_api_key');

            try {
                if ($apiKey) {
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                    ])->timeout(15)->post('https://api.bdcourier.com/courier-check', [
                        'phone' => $phone,
                    ]);

                    $payload = $response->json();

                    if (is_array($payload) && (($payload['status'] ?? '') === 'error')) {
                        $error = $payload['message'] ?? __('BD Courier API returned an error response.');
                    } elseif ($response->successful() && is_array($payload)) {
                        $result = $payload;
                    } else {
                        $error = __('BD Courier API returned status :status.', ['status' => $response->status()]);
                    }
                } else {
                    $error = __('BD Courier API key is not configured.');
                }
            } catch (\Throwable $e) {
                $error = __('BD Courier API request failed.');
            }
        }

        return view('backend.fraud_checker.index', compact('phone', 'result', 'error', 'matchedOrders'));
    }

    public function updateFraudStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'is_verified_fraud' => 'required|in:fraud,not_verified,verified',
        ]);

        $order = Order::findOrFail($request->order_id);
        $order->is_verified_fraud = $request->is_verified_fraud;
        $order->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('Fraud status updated successfully.'),
                'new_status' => $order->is_verified_fraud,
            ]);
        }

        return redirect()->back()->with('success', __('Fraud status updated successfully.'));
    }
}
