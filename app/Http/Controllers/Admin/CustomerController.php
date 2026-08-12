<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Customer;
use Illuminate\Http\Request;
use App\Exports\CustomerExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        $timeFilter = $request->get('time_filter', 'all');

        // return $asdufh = Customer::with('orders')->get();

        $query = Customer::with('orders')->with('user');

        if ($timeFilter != 'all') {
            switch ($timeFilter) {
                case '7days':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case '30days':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('phone', 'like', "%{$search}%");
        }

        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'active') {
                $query->where('banned', false);
            } elseif ($request->status == 'banned') {
                $query->where('banned', true);
            }
        }

        if ($request->has('balance_filter') && $request->balance_filter != '') {
            if ($request->balance_filter == 'has_balance') {
                $query->where('balance', '>', 0);
            } elseif ($request->balance_filter == 'no_balance') {
                $query->where('balance', 0);
            }
        }

        $sortBy = $request->get('sort_by', 'latest');

        switch ($sortBy) {
            case 'latest':
                $query->latest('id');
                break;
            case 'oldest':
                $query->oldest('id');
                break;
            case 'name_asc':
                $query->join('users', 'customers.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc')
                    ->select('customers.*');
                break;
            case 'name_desc':
                $query->join('users', 'customers.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc')
                    ->select('customers.*');
                break;
            case 'balance_high':
                $query->orderBy('balance', 'desc');
                break;
            case 'balance_low':
                $query->orderBy('balance', 'asc');
                break;
            default:
                $query->latest('id');
                break;
        }

        $customers = $query->paginate(15)->appends($request->query());

        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('banned', false)->count();
        $bannedCustomers = Customer::where('banned', true)->count();
        $customersWithBalance = Customer::where('balance', '>', 0)->count();
        $totalBalance = Customer::sum('balance');

        $last7DaysCustomers = Customer::where('created_at', '>=', now()->subDays(7))->count();
        $last30DaysCustomers = Customer::where('created_at', '>=', now()->subDays(30))->count();
        $lastYearCustomers = Customer::whereYear('created_at', now()->year)->count();

        return view('backend.customers.index', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'bannedCustomers',
            'customersWithBalance',
            'totalBalance',
            'timeFilter',
            'last7DaysCustomers',
            'last30DaysCustomers',
            'lastYearCustomers'
        ));
    }



    /**
     * Update customer
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($customer->user_id ?? 'NULL'),
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'balance' => 'nullable|numeric|min:0',
        ]);

        try {
            $customer = Customer::findOrFail($id);

            // Update user information
            if ($customer->user) {
                $userData = [
                    'name' => $request->name,
                    'email' => $request->email,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = bcrypt($request->password);
                }

                $customer->user->update($userData);
            }

            // Update customer information
            $customer->update([
                'phone' => $request->phone,
                'address' => $request->address,
                'country' => $request->country,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'balance' => $request->balance ?? $customer->balance,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer updated successfully!'
                ]);
            }

            flash(translate('Customer updated successfully!'))->success();
            return redirect()->route('customers.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }

            flash(translate('Error: ' . $e->getMessage()))->error();
            return back();
        }
    }

    /**
     * Display customer details
     */
    public function show($id)
    {
        $customer = Customer::with([
            'user',
            'orders.orderDetails.product'
        ])->findOrFail($id);

        $topProducts = collect();
        if ($customer->orders->isNotEmpty()) {
            $allDetails = $customer->orders->flatMap(function ($order) {
                return $order->orderDetails;
            });

            $topProducts = $allDetails->groupBy('product_id')
                ->map(function ($details) {
                    $product = $details->first()->product;
                    return [
                        'product' => $product,
                        'count' => $details->count(),
                        'total_quantity' => $details->sum('quantity'),
                    ];
                })
                ->sortByDesc('count')
                ->take(5);
        }
        return view('backend.customers.show', compact('customer', 'topProducts'));
    }

    /**
     * Edit customer
     */
    public function edit($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        return view('backend.customers.edit', compact('customer'));
    }

    /**
     * Update customer
     */
    public function update1(Request $request, $id)
    {
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'balance' => 'nullable|numeric|min:0',
        ]);

        try {
            $customer = Customer::findOrFail($id);
            $customer->update([
                'phone' => $request->phone,
                'address' => $request->address,
                'country' => $request->country,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'balance' => $request->balance ?? $customer->balance,
            ]);

            // Update user phone if needed
            if ($customer->user && $request->phone) {
                $customer->user->update(['phone' => $request->phone]);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer updated successfully!'
                ]);
            }

            flash(translate('Customer updated successfully!'))->success();
            return redirect()->route('customers.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }

            flash(translate('Error: ' . $e->getMessage()))->error();
            return back();
        }
    }

    /**
     * Delete customer
     */
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            // Optionally delete the user as well
            // if ($customer->user) {
            //     $customer->user->delete();
            // }

            $customer->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer deleted successfully!'
                ]);
            }

            flash(translate('Customer deleted successfully!'))->success();
            return redirect()->route('customers.index');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }

            flash(translate('Error: ' . $e->getMessage()))->error();
            return back();
        }
    }

    /**
     * Toggle customer ban status
     */
    public function toggleBan($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->banned = !$customer->banned;
            $customer->save();

            $status = $customer->banned ? 'banned' : 'activated';

            return response()->json([
                'success' => true,
                'message' => "Customer {$status} successfully!",
                'banned' => $customer->banned
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk action (delete, ban, activate)
     */
    public function bulkAction(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:customers,id',
                'action' => 'required|in:delete,ban,activate'
            ]);

            $count = 0;

            if ($request->action == 'delete') {
                $count = Customer::whereIn('id', $request->ids)->delete();
                $message = $count . ' customers deleted successfully!';
            } elseif ($request->action == 'ban') {
                $count = Customer::whereIn('id', $request->ids)->update(['banned' => true]);
                $message = $count . ' customers banned successfully!';
            } elseif ($request->action == 'activate') {
                $count = Customer::whereIn('id', $request->ids)->update(['banned' => false]);
                $message = $count . ' customers activated successfully!';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export customers to CSV/Excel
     */
    public function export()
    {
        return Excel::download(
            new CustomerExport,
            'customers_' . date('Y-m-d') . '.xlsx'
        );
    }


    /**
     * Update customer balance
     */
    public function updateBalance(Request $request, $id)
    {
        try {
            $request->validate([
                'adjustment_type' => 'required|in:add,subtract,set',
                'amount' => 'required|numeric|min:0',
                'note' => 'nullable|string'
            ]);

            $customer = Customer::findOrFail($id);
            $oldBalance = $customer->balance;
            $amount = $request->amount;

            switch ($request->adjustment_type) {
                case 'add':
                    $customer->balance += $amount;
                    $message = "Added ৳" . number_format($amount, 2) . " to balance";
                    break;
                case 'subtract':
                    if ($customer->balance < $amount) {
                        throw new \Exception('Insufficient balance');
                    }
                    $customer->balance -= $amount;
                    $message = "Subtracted ৳" . number_format($amount, 2) . " from balance";
                    break;
                case 'set':
                    $customer->balance = $amount;
                    $message = "Balance set to ৳" . number_format($amount, 2);
                    break;
            }

            $customer->save();

            // Log balance adjustment (if you have a balance history table)
            // BalanceHistory::create([
            //     'customer_id' => $customer->id,
            //     'old_balance' => $oldBalance,
            //     'new_balance' => $customer->balance,
            //     'amount' => $amount,
            //     'type' => $request->adjustment_type,
            //     'note' => $request->note,
            //     'created_by' => auth()->id()
            // ]);

            flash(translate($message . ' successfully!'))->success();
            return redirect()->route('customers.show', $customer->id);
        } catch (\Exception $e) {
            flash(translate('Error: ' . $e->getMessage()))->error();
            return back();
        }
    }

    // Additional methods for bulk SMS functionality

    /**
     * Send bulk SMS to selected customers
     */
    public function bulkSms(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:customers,id',
                'message' => 'required|string|max:500',
                'include_name' => 'nullable|boolean',
                'include_balance' => 'nullable|boolean'
            ]);

            $customers = Customer::with('user')
                ->whereIn('id', $request->ids)
                ->get();

            $successCount = 0;
            $failedCount = 0;
            $failedNumbers = [];

            foreach ($customers as $customer) {
                $phone = $customer->phone ?? $customer->user->phone ?? null;

                if (!$phone) {
                    $failedCount++;
                    $failedNumbers[] = $customer->user->name ?? 'Unknown';
                    continue;
                }

                // Prepare message with replacements
                $message = $request->message;

                if ($request->include_name) {
                    $customerName = $customer->user->name ?? 'Customer';
                    $message = str_replace('{name}', $customerName, $message);
                }

                if ($request->include_balance) {
                    $balance = number_format($customer->balance, 2);
                    $message = str_replace('{balance}', $balance, $message);
                }

                // Send SMS via API (Example with multiple providers)
                $sent = $this->sendSms($phone, $message);

                if ($sent) {
                    $successCount++;
                } else {
                    $failedCount++;
                    $failedNumbers[] = $phone;
                }
            }

            $responseMessage = "SMS sent to {$successCount} customers";
            if ($failedCount > 0) {
                $responseMessage .= ". Failed: {$failedCount}";
            }

            return response()->json([
                'success' => true,
                'message' => $responseMessage,
                'success_count' => $successCount,
                'failed_count' => $failedCount,
                'failed_numbers' => $failedNumbers
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk SMS Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send SMS using API (Supports multiple providers)
     */
    private function sendSms($phone, $message)
    {
        // Clean phone number (remove spaces, special characters)
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Check if phone number is valid (Bangladeshi format)
        if (strlen($phone) === 11 && substr($phone, 0, 1) === '0') {
            $phone = '88' . $phone; // Convert to international format
        }

        if (strlen($phone) !== 13) {
            return false;
        }

        // Option 1: Using a SMS Gateway API (Example with Twilio)
        // $twilioSid = config('services.twilio.sid');
        // $twilioToken = config('services.twilio.token');
        // $twilioFrom = config('services.twilio.from');

        // try {
        //     $twilio = new Client($twilioSid, $twilioToken);
        //     $twilio->messages->create($phone, [
        //         'from' => $twilioFrom,
        //         'body' => $message
        //     ]);
        //     return true;
        // } catch (\Exception $e) {
        //     Log::error("SMS failed to {$phone}: " . $e->getMessage());
        //     return false;
        // }

        // Option 2: Using a local SMS provider (Example with SSL Wireless)
        // $apiKey = config('services.ssl_sms.api_key');
        // $sid = config('services.ssl_sms.sid');
        // $url = "https://api.sslwireless.com/sms/send";

        // $response = Http::post($url, [
        //     'api_key' => $apiKey,
        //     'sid' => $sid,
        //     'msisdn' => $phone,
        //     'sms' => $message,
        //     'csms_id' => uniqid()
        // ]);

        // return $response->successful();
        return true;
    }

    /**
     * Get customer names for SMS preview (AJAX)
     */
    public function getCustomerNames(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:customers,id'
            ]);

            $customers = Customer::with('user')
                ->whereIn('id', $request->ids)
                ->get()
                ->map(function ($customer) {
                    return [
                        'id' => $customer->id,
                        'name' => $customer->user->name ?? 'Customer',
                        'phone' => $customer->phone ?? $customer->user->phone ?? null,
                        'balance' => $customer->balance
                    ];
                });

            return response()->json([
                'success' => true,
                'customers' => $customers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
