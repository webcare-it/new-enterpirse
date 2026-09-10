<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentSystemResource;
use App\Models\Admin\Category;
use App\Models\Admin\SectionConfig;
use App\Models\BusinessSetting;
use App\Http\Resources\BrandResource;
use App\Models\Admin\Brand;
use App\Models\Page;
use App\Models\PaymentSystem;
use App\Models\ShippingCost;
use App\Models\Upload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Session;


class BusinessSettingController extends Controller
{
    public function businessSettings(Request $request)
    {
        // Allowed setting keys (non-image)
        $allowed = [
            'website_name',
            'facebook_link',
            'twitter_link',
            'youtube_link',
            'instagram_link',
            'linkedin_link',
            'maintenance_mode',
            'header_logo',
            'footer_logo',
            'site_icon',
            'helpline_number',
            'payment_method_images',
            'frontend_copyright_text',
            'contact_email',
            'contact_phone',
            'contact_address',
            'base_color',
            'base_hov_color',
            'top_bar_offer',
            'b_opening_hour',
            'b_closing_hour',
            'n_sub_title',
            'fb_page_username',
            'whatsapp_number',
            'otp_for_order',
            'forget_password_otp',
            'phone_verification_otp'
        ];

        // Keys that store image IDs (will be converted to URLs)
        $imageKeys = [
            'header_logo',
            'footer_logo',
            'site_icon',
            'payment_method_images'
        ];

        // ─── Shipping costs ───
        $shippings = ShippingCost::select(['id', 'name', 'amount'])
            ->where('status', 1)
            ->latest('id')
            ->get();

        // ─── Payment systems ───
        $payments = PaymentSystem::all()->map(function ($ps) {
            return [
                'id'         => $ps->id,
                'title'      => $ps->title,
                'type'       => $ps->type,
                'image'      => $ps->image ? uploaded_asset($ps->image) : null,
                'is_default' => (bool) $ps->is_default,
            ];
        });

        // ─── Section config ───
        $section_config = SectionConfig::select('key', 'order', 'isActive')
            ->oldest('order')
            ->get();

        // ─── Business settings (only allowed keys) ───
        $settings = BusinessSetting::all()
            ->pluck('value', 'type')
            ->only($allowed)
            ->toArray();

        // ─── Convert image IDs to URLs ───
        foreach ($imageKeys as $key) {
            if (!empty($settings[$key]) && is_numeric($settings[$key])) {
                $settings[$key] = uploaded_asset($settings[$key]);
            }
        }

        // ─── Categories ───
        $categories = Category::select(['id', 'slug', 'category_name', 'category_image'])
            ->oldest('position')
            ->get()
            ->map(function ($category) {
                return [
                    'id'    => $category->id,
                    'slug'  => $category->slug,
                    'name'  => $category->category_name,
                    'image' => $category->category_image  ? uploaded_asset($category->category_image) : null,
                ];
            });

        // ─── Currency settings ───
        $currency = BusinessSetting::whereIn('type', [
            'currency',
            'currency_symbol',
            'is_currency_symbol',
            'currency_position',
            'is_decimal',
            'decimal_digits'
        ])->pluck('value', 'type');

        $currency_setting = $currency->toArray();

        $booleanKeys = ['is_currency_symbol', 'is_decimal'];

        foreach ($booleanKeys as $key) {
            if (array_key_exists($key, $currency_setting)) {
                $currency_setting[$key] = filter_var($currency_setting[$key], FILTER_VALIDATE_BOOLEAN);
            }
        }

        // ─── Layout breakpoints (new) ───
        $defaultLayout = [
            'homePage'    => ['mobile' => 1, 'tablet' => 2, 'laptop' => 3, 'desktop' => 4, 'ultrawide' => 5],
            'withFilter'  => ['mobile' => 2, 'tablet' => 2, 'laptop' => 3, 'desktop' => 4, 'ultrawide' => 5],
            'fullLayout'  => ['mobile' => 2, 'tablet' => 3, 'laptop' => 4, 'desktop' => 5, 'ultrawide' => 6],
        ];

        $layoutConfig = json_decode(get_setting('layout_breakpoints'), true);
        if (!is_array($layoutConfig) || empty($layoutConfig)) {
            $layoutConfig = $defaultLayout;
        }

        $customer_ip = request()->ip();
        $session = Session::getId();

        Session::put('session_id', $session);

        if (Session::get('session_id'))
        {
            $session_id = Session::get('session_id');
        }

        // ─── Assemble final data array ───
        $data = $settings;
        $data['sections']           = $section_config;
        $data['categories']         = $categories;
        $data['shippings']          = $shippings;
        $data['payments']           = $payments;
        $data['currency_setting']   = $currency_setting;
        $data['brands']             = BrandResource::collection(Brand::all())->resolve();
        $data['layout_breakpoints'] = $layoutConfig;
        $data['customer_ip']        = $customer_ip;
        $data['session_id']         = $session_id;

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }


    public function policy(Request $request): JsonResponse
    {
        $type = $request->query('type');

        if (!$type) {
            $data = Page::whereIn('type', ['privacy_policy', 'refund_policy', 'return_policy', 'terms_and_conditions'])->latest()->get();
            return response()->json([
                'success' => true,
                'message' => 'Successfully Data Retrieve',
                'data' => $data
            ]);
        } else {
            $data = Page::where('type', $type)->firstOrFail();
            return response()->json([
                'success' => true,
                'message' => 'Successfully Data Retrieve',
                'data' => $data
            ]);
        }
    }
}
