<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cart;
use App\Models\Admin\Customer;
use App\Models\Admin\Order;
use App\Models\Admin\Wishlist;
use App\Models\BusinessSetting;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    /**
     * Register a new user (customer).
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|email|unique:users,email',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $otp_enabled = get_setting('phone_verification_otp') == 1;

        $user = User::create([
            'user_type'         => 'customer',
            'name'              => $request->name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'password'          => Hash::make($request->password),
            'referral_code'     => $this->generateReferralCode(),
            'banned'            => 0,
            'verification_code' => $otp_enabled ? rand(100000, 999999) : null,
        ]);

        if ($otp_enabled) {
            SmsService::phone_number_verification($user);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your phone.',
                'data'    => [
                    'otp_sent'  => true,
                    'user_id'   => $user->id,
                    'phone'     => $request->phone,
                ],
            ], 201);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'data'    => [
                'user'       => $user,
                'token'      => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    /**
     * Verify OTP after registration.
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'otp'     => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::find($request->user_id);

        if ($user->verification_code != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP code.',
            ], 422);
        }

        $user->verification_code = null;
        $user->email_verified_at = now();
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Phone verified successfully.',
            'data'    => [
                'user'       => $user,
                'token'      => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    /**
     * Resend OTP to phone.
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::find($request->user_id);
        $user->verification_code = rand(100000, 999999);
        $user->save();

        SmsService::phone_number_verification($user);

        return response()->json([
            'success' => true,
            'message' => 'OTP resent successfully.',
        ]);
    }

    /**
     * Login user and issue token.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'nullable|email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        if (!$request->email && !$request->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide either email or phone.',
            ], 422);
        }

        if ($request->email) {
            $login = $request->email;
            $field = 'email';
        } else {
            $login = $request->phone;
            $field = 'phone';
        }

        $user = User::where($field, $login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        if ($user->banned) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been banned.',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data'    => [
                'user'  => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    public function google(Request $request)
    {
        try {
            $googleClientId     = env('GOOGLE_CLIENT_ID');
            $googleClientSecret = env('GOOGLE_CLIENT_SECRET');
            $redirectUri        = url('/api/v1/auth/oauth/google');
            $frontendUrl        = env('APP_URL') . 'redirect';

            if (!$request->filled('code')) {
                $params = http_build_query([
                    'client_id'     => $googleClientId,
                    'redirect_uri'  => $redirectUri,
                    'response_type' => 'code',
                    'scope'         => 'openid email profile',
                    'state'         => json_encode(['type' => 'google']),
                    'prompt'        => 'select_account',
                ]);

                return redirect('https://accounts.google.com/o/oauth2/auth?' . $params);
            }

            $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code'          => $request->input('code'),
                'client_id'     => $googleClientId,
                'client_secret' => $googleClientSecret,
                'redirect_uri'  => $redirectUri,
                'grant_type'    => 'authorization_code',
            ]);

            if (!$tokenResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to exchange authorization code with Google.',
                ], 400);
            }

            $userInfo = Http::withToken($tokenResponse->json('access_token'))
                ->get('https://www.googleapis.com/oauth2/v3/userinfo');

            if (!$userInfo->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch user info from Google.',
                ], 400);
            }

            $providerId = $userInfo->json('sub');
            $name       = $userInfo->json('name') ?: trim(($userInfo->json('given_name') ?? '') . ' ' . ($userInfo->json('family_name') ?? ''));
            $email      = $userInfo->json('email');

            $user = User::where('provider_id', $providerId)->first();

            if (!$user) {
                $user = $email ? User::where('email', $email)->first() : null;

                if ($user) {
                    $user->update(['provider_id' => $providerId]);
                } else {
                    $user = User::create([
                        'user_type'         => 'customer',
                        'name'              => $name ?: 'User',
                        'email'             => $email,
                        'provider_id'       => $providerId,
                        'password'          => Hash::make(Str::random(32)),
                        'referral_code'     => $this->generateReferralCode(),
                        'email_verified_at' => now(),
                        'banned'            => 0,
                    ]);
                }
            }

            if ($user->banned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account has been banned.',
                ], 403);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return redirect($frontendUrl . '?token=' . urlencode($token) . '&user_id=' . $user->id);

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong during Google login. Please try again.',
            ], 500);
        }
    }
    public function facebook(Request $request)
    {
        try {
            $facebookAppId     = env('FACEBOOK_CLIENT_ID');
            $facebookAppSecret = env('FACEBOOK_CLIENT_SECRET');
            $redirectUri       = url('/api/v1/auth/oauth/facebook');
            $frontendUrl       = env('APP_URL') . 'redirect';

            if (!$request->filled('code')) {
                $params = http_build_query([
                    'client_id'     => $facebookAppId,
                    'redirect_uri'  => $redirectUri,
                    'response_type' => 'code',
                    'scope'         => 'email,public_profile',
                    'state'         => json_encode(['type' => 'facebook']),
                ]);

                return redirect('https://www.facebook.com/v18.0/dialog/oauth?' . $params);
            }

            $tokenResponse = Http::get('https://graph.facebook.com/v18.0/oauth/access_token', [
                'code'          => $request->input('code'),
                'client_id'     => $facebookAppId,
                'client_secret' => $facebookAppSecret,
                'redirect_uri'  => $redirectUri,
            ]);

            if (!$tokenResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to exchange authorization code with Facebook.',
                ], 400);
            }

            $userInfo = Http::get('https://graph.facebook.com/v18.0/me', [
                'fields'       => 'id,name,email,picture',
                'access_token' => $tokenResponse->json('access_token'),
            ]);

            if (!$userInfo->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch user info from Facebook.',
                ], 400);
            }

            $providerId = $userInfo->json('id');
            $name       = $userInfo->json('name');
            $email      = $userInfo->json('email');

            $user = User::where('provider_id', $providerId)->first();

            if (!$user) {
                $user = $email ? User::where('email', $email)->first() : null;

                if ($user) {
                    $user->update(['provider_id' => $providerId]);
                } else {
                    $user = User::create([
                        'user_type'         => 'customer',
                        'name'              => $name ?: 'User',
                        'email'             => $email,
                        'provider_id'       => $providerId,
                        'password'          => Hash::make(Str::random(32)),
                        'referral_code'     => $this->generateReferralCode(),
                        'email_verified_at' => now(),
                        'banned'            => 0,
                    ]);
                }
            }

            if ($user->banned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account has been banned.',
                ], 403);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return redirect($frontendUrl . '?token=' . urlencode($token) . '&user_id=' . $user->id);

        } catch (\Exception $e) {
            // Log the real error
            \Log::error('Facebook OAuth Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong during Facebook login. Please try again.',
            ], 500);
        }
    }
    /**
     * Logout user (revoke token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Get authenticated user details.
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        $customer = Customer::where('user_id', $user->id)->first();

        $loyaltySetting = BusinessSetting::where('type', 'loyalty')->first();
        $thresholds = [];
        if ($loyaltySetting && !empty($loyaltySetting->value)) {
            $thresholds = json_decode($loyaltySetting->value, true);
        }

        if ($user->avatar) {
            $user->avatar = asset('public/' . $user->avatar);
        }

        $userPoints = $customer->point ?? 0;

        $steps = [];
        $currentMemberType = 'Regular';

        if (!empty($thresholds)) {
            $priorityOrder = ['Diamond', 'Gold', 'Star'];
            $sorted = [];

            foreach ($priorityOrder as $tier) {
                if (array_key_exists($tier, $thresholds)) {
                    $sorted[$tier] = $thresholds[$tier];
                }
            }
            foreach ($thresholds as $tier => $point) {
                if (!in_array($tier, $priorityOrder)) {
                    $sorted[$tier] = $point;
                }
            }

            foreach ($sorted as $tier => $point) {
                if ($tier === 'Star') {
                    $completed = $userPoints < $point ? true : ($userPoints >= $point);
                } else {
                    $completed = ($userPoints >= $point);
                }

                $steps[] = [
                    'member_type' => $tier,
                    'point'       => (int)$point,
                    'completed'   => $completed,
                ];
            }

            usort($steps, function ($a, $b) {
                return $a['point'] - $b['point'];
            });

            foreach ($sorted as $tier => $point) {
                if ($userPoints >= $point) {
                    $currentMemberType = $tier;
                }
            }
        }

        $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->where('delivery_status', 'pending')->count();
        $inProgress = $user->orders()->whereIn('delivery_status', ['processing', 'shipped'])->count();
        $completedOrders = $user->orders()->where('delivery_status', 'completed')->count();
        $successfulDeliveries = $user->orders()->whereIn('delivery_status', ['completed', 'delivered'])->count();
        $totalSpent = (float) $user->orders()->sum('grand_total');
        $successRate = $totalOrders > 0 ? round(($successfulDeliveries / $totalOrders) * 100, 2) : 0;

        $cartTotalQuantity = (int) Cart::where('user_id', $user->id)->sum('quantity');
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        $recentOrders = $user->orders()
            ->select('id', 'code', 'grand_total', 'payment_status', 'delivery_status', 'created_at')
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'summary' => [
                    'total_orders'          => $totalOrders,
                    'pending_orders'        => $pendingOrders,
                    'in_progress'           => $inProgress,
                    'completed_orders'      => $completedOrders,
                    'successful_deliveries' => $successfulDeliveries,
                    'total_amount_spent'    => $totalSpent,
                    'cart_items'            => $cartTotalQuantity,
                    'success_rate'          => $successRate,
                    'wishlist_items'        => $wishlistCount,
                    'earn_point'            => $userPoints,
                ],
                'steps' => $steps,
                'current_member_type' => $currentMemberType,
                'recent_orders' => $recentOrders,
            ],
        ]);
    }

    /**
     * Get dashboard statistics for the authenticated user.
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        $purchaseHistory = Order::where('user_id', $user->id)->with('orderDetails')->get();

        $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->where('delivery_status', 'pending')->count();
        $inProgress    = $user->orders()->whereIn('delivery_status', ['processing', 'shipped'])->count();
        $completed     = $user->orders()->where('delivery_status', 'completed')->count();
        $successfulDeliveries = $user->orders()->whereIn('delivery_status', ['completed', 'delivered'])->count();
        $totalSpent = (float) $user->orders()->sum('grand_total');


        $cartTotalQuantity = Cart::where('user_id', $user->id)->sum('quantity');

        $wishlistCount = Wishlist::where('user_id', $user->id)->count();

        $savedItemsCount = 0;

        $recentOrders = $user->orders()
            ->select('id', 'code', 'grand_total', 'payment_status', 'delivery_status', 'created_at')
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'user'                   => $user,
                'total_orders'           => $totalOrders,
                'pending_orders'         => $pendingOrders,
                'in_progress'            => $inProgress,
                'completed_orders'       => $completed,
                'successful_deliveries'  => $successfulDeliveries,
                'total_amount_spent'     => $totalSpent,
                'cart_items'             => $cartTotalQuantity,
                'saved_items'            => $savedItemsCount,
                'wishlist_items'         => $wishlistCount,
                'recent_orders'          => $recentOrders,
                'purchaseHistory'          => $purchaseHistory
            ],
        ]);
    }

    /**
     * Generate a unique referral code.
     */
    private function generateReferralCode()
    {
        do {
            $code = 'REF-' . strtoupper(\Illuminate\Support\Str::random(6));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    public function profileUpdate(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name'     => 'nullable|string|max:255',
            'email'    => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone'    => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'address'  => 'nullable|string',
            'city'     => 'nullable|string|max:255',
            'state'    => 'nullable|string|max:255',
            'country'  => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'avatar'   => 'nullable|string', // base64 encoded image
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();


        if (!empty($data['avatar'])) {
            $avatarBase64 = $data['avatar'];
            if (preg_match('/^data:image\/(\w+);base64,/', $avatarBase64, $matches)) {
                $imageType = $matches[1];
                $imageData = substr($avatarBase64, strpos($avatarBase64, ',') + 1);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    return response()->json(['errors' => ['avatar' => 'Invalid base64 data']], 422);
                }

                $directory = public_path('avatars');
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }

                $filename = $user->id . '_' . Str::random(20) . '.' . $imageType;
                $filePath = $directory . '/' . $filename;

                if (file_put_contents($filePath, $imageData) === false) {
                    return response()->json(['errors' => ['avatar' => 'Failed to save image']], 500);
                }

                if ($user->avatar && file_exists(public_path($user->avatar))) {
                    unlink(public_path($user->avatar));
                }

                $data['avatar'] = 'avatars/' . $filename;
            } else {
                return response()->json(['errors' => ['avatar' => 'Invalid image format (must be base64)']], 422);
            }
        } else {
            unset($data['avatar']);
        }

        $user->update($data);

        $user->avatar_url = $user->avatar ? asset($user->avatar) : null;

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }


    /**
     * Change authenticated user's password.
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
            ], 401);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }
}
