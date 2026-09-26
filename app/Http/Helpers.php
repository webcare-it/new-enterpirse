<?php

use App\Models\BusinessSetting;
use App\Models\Upload;
use App\Models\User;
use App\Utility\NotificationUtility;


/**
 * API Success Response
 */
if (!function_exists('successResponse')) {
    function successResponse($message = 'Success', $data = [], $code = 200)
    {
        return response()->json([
            'status'  => $code,
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }
}

/**
 * API Error Response
 */
if (!function_exists('errorResponse')) {
    function errorResponse($message = 'Something went wrong', $errors = [], $code = 400)
    {
        return response()->json([
            'status'  => $code,
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}


if (!function_exists('verified_sellers_id')) {
    function verified_sellers_id()
    {
        return Cache::rememberForever('verified_sellers_id', function () {
            return User::where('verification_status', 1)->pluck('user_id')->toArray();
        });
    }
}

//filter products based on vendor activation system
if (!function_exists('filter_products')) {
    function filter_products($products)
    {
        $verified_sellers = verified_sellers_id();
        if (get_setting('vendor_system_activation') == 1) {
            return $products->where('approved', '1')->where('published', '1')->where('auction_product', 0)->orderBy('created_at', 'desc')->where(function ($p) use ($verified_sellers) {
                $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                    $q->whereIn('user_id', $verified_sellers);
                });
            });
        } else {
            return $products->where('published', '1')->where('auction_product', 0)->where('added_by', 'admin');
        }
    }
}


if (!function_exists('serveReact')) {
    function serveReact(string $route = 'home', string $slug = ''): string
    {
        $baseUrl  = rtrim(request()->getSchemeAndHttpHost(), '/');
        $appName  = get_setting('site_name') ?: config('app.name');

        $globalTitle       = get_setting('meta_title')       ?: $appName;
        $globalDescription = get_setting('meta_description') ?: 'Welcome to ' . $appName . '';
        $globalMetaImage   = get_setting('meta_image')       ? uploaded_asset(get_setting('meta_image')) : null;
        $globalOgImage     = $globalMetaImage ?: ($baseUrl . '/assets/img/logo.png');
        $globalFavicon     = get_setting('site_icon')        ? uploaded_asset(get_setting('site_icon'))  : ($baseUrl . '/assets/img/logo.png');

        $meta = [
            'title'       => $globalTitle,
            'description' => $globalDescription,
            'image'       => $globalOgImage,
            'url'         => $baseUrl . request()->getRequestUri(),
            'type'        => 'website',
            'favicon'     => $globalFavicon,
        ];

        $htmlPath = public_path('app/index.html');
        if (!file_exists($htmlPath)) {
            abort(503, 'App not built yet.');
        }

        $html = file_get_contents($htmlPath);

        $faviconTag = '<link rel="icon" type="image/png" href="' . htmlspecialchars($meta['favicon']) . '" />';

        $metaTags = '
        <title>' . htmlspecialchars($meta['title']) . '</title>
        <link rel="canonical" href="' . htmlspecialchars($meta['url']) . '" />
        <meta name="description" content="' . htmlspecialchars($meta['description']) . '" />
        <meta name="keywords" content="' . htmlspecialchars(get_setting('meta_keywords') ?: 'online shopping, e-commerce, buy online') . '" />
        <meta property="og:title" content="' . htmlspecialchars($meta['title']) . '" />
        <meta property="og:description" content="' . htmlspecialchars($meta['description']) . '" />
        <meta property="og:image" content="' . htmlspecialchars($meta['image']) . '" />
        <meta property="og:url" content="' . htmlspecialchars($meta['url']) . '" />
        <meta property="og:type" content="' . htmlspecialchars($meta['type']) . '" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="' . htmlspecialchars($meta['title']) . '" />
        <meta name="twitter:description" content="' . htmlspecialchars($meta['description']) . '" />
        <meta name="twitter:image" content="' . htmlspecialchars($meta['image']) . '" />
        ' . $faviconTag;

        $html = preg_replace('/<title>.*?<\/title>/s', '', $html);
        $html = preg_replace('/<meta\s+name="description"[^>]*>/i', '', $html);
        $html = preg_replace('/<meta\s+name="keywords"[^>]*>/i', '', $html);
        $html = preg_replace('/<meta\s+property="og:[^"]*"[^>]*>/i', '', $html);
        $html = preg_replace('/<meta\s+name="twitter:[^"]*"[^>]*>/i', '', $html);
        $html = preg_replace('/<link\s+rel="canonical"[^>]*>/i', '', $html);
        $html = preg_replace('/<link\s+rel="(shortcut\s+)?icon"[^>]*>/i', '', $html);
        $html = str_replace('</head>', $metaTags . "\n</head>", $html);

        return $html;
    }
}


//highlights the selected navigation on admin panel
if (!function_exists('areActiveRoutes')) {
    function areActiveRoutes(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}

//highlights the selected navigation on frontend
if (!function_exists('areActiveRoutesHome')) {
    function areActiveRoutesHome(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}

//highlights the selected navigation on frontend
if (!function_exists('default_language')) {
    function default_language()
    {
        return env("DEFAULT_LANGUAGE");
    }
}


function translate($key, $lang = null)
{
    // Return the key directly without translation when translation system is off
    return $key;
}

function remove_invalid_charcaters($str)
{
    $str = str_ireplace(array("\\"), '', $str);
    return str_ireplace(array('"'), '\"', $str);
}


function timezones()
{
    return Timezones::timezonesToArray();
}

if (!function_exists('app_timezone')) {
    function app_timezone()
    {
        return config('app.timezone');
    }
}

if (!function_exists('api_asset')) {
    function api_asset($id)
    {
        if (($asset = \App\Models\Upload::find($id)) != null) {
            return $asset->file_name;
        }
        return "";
    }
}

//return file uploaded via uploader
if (!function_exists('uploaded_asset')) {
    function uploaded_asset($id)
    {
        if ($id === null || $id === '') {
            return null;
        }

        $preloaded = preload_uploaded_assets();
        if ((is_int($id) || (is_string($id) && ctype_digit($id))) && array_key_exists((int) $id, $preloaded)) {
            return $preloaded[(int) $id];
        }

        if (($asset = \App\Models\Upload::find($id)) != null) {
            return my_asset($asset->file_name);
        }
        return null;
    }
}

//load many uploads with one query so the uploaded_asset() calls that follow skip the database
if (!function_exists('preload_uploaded_assets')) {
    function preload_uploaded_assets($ids = [])
    {
        static $urls = [];

        $missing = [];
        foreach ($ids as $id) {
            if ((is_int($id) || (is_string($id) && ctype_digit($id))) && !array_key_exists((int) $id, $urls)) {
                $missing[(int) $id] = (int) $id;
            }
        }

        if ($missing) {
            $fileNames = \App\Models\Upload::whereIn('id', array_values($missing))->pluck('file_name', 'id');
            foreach ($missing as $id) {
                $urls[$id] = $fileNames->has($id) ? my_asset($fileNames[$id]) : null;
            }
        }

        return $urls;
    }
}

if (!function_exists('my_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function my_asset($path, $secure = null)
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return Storage::disk('s3')->url($path);
        } else {
            return rtrim(app('url')->asset('/' . ltrim($path, '/'), $secure), '/');
        }
    }
}

if (!function_exists('static_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function static_asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}

if (!function_exists('getBaseURL')) {
    function getBaseURL()
    {
        // Use Laravel's URL generator — reliable on cPanel where SCRIPT_NAME includes /public/
        return rtrim(url('/'), '/');
    }
}


if (!function_exists('getFileBaseURL')) {
    function getFileBaseURL()
    {
        if (env('FILESYSTEM_DRIVER') == 's3') {
            return env('AWS_URL') . '/';
        } else {
            return getBaseURL() . '/';
        }
    }
}


if (!function_exists('isUnique')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function isUnique($email)
    {
        $user = \App\Models\User::where('email', $email)->first();

        if ($user == null) {
            return '1'; // $user = null means we did not get any match with the email provided by the user inside the database
        } else {
            return '0';
        }
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null, $lang = false)
    {
        $settings = Cache::remember('business_settings', 86400, function () {
            return BusinessSetting::all();
        });

        if ($lang == false) {
            $setting = $settings->where('type', $key)->first();
        } else {
            $setting = $settings->where('type', $key)->where('lang', $lang)->first();
            $setting = !$setting ? $settings->where('type', $key)->first() : $setting;
        }
        return $setting == null ? $default : $setting->value;
    }
}


function hex2rgba($color, $opacity = false)
{
    return Colorcodeconverter::convertHexToRgba($color, $opacity);
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        if (Auth::check() && (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')) {
            return true;
        }
        return false;
    }
}


if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

// duplicates m$ excel's ceiling function
if (!function_exists('ceiling')) {
    function ceiling($number, $significance = 1)
    {
        return (is_numeric($number) && is_numeric($significance)) ? (ceil($number / $significance) * $significance) : false;
    }
}

if (!function_exists('get_images')) {
    function get_images($given_ids, $with_trashed = false)
    {
        if (is_array($given_ids)) {
            $ids = $given_ids;
        } elseif ($given_ids == null) {
            $ids = [];
        } else {
            $ids = explode(",", $given_ids);
        }


        return $with_trashed
            ? Upload::withTrashed()->whereIn('id', $ids)->get()
            : Upload::whereIn('id', $ids)->get();
    }
}

if (!function_exists('sendSMS')) {
    function sendSMS($number, $sendername, $message, $template_id = '')
    {
        $active_provider = \App\Models\OtpConfiguration::where('type', 'active_provider')->value('value');

        if ($active_provider == null) {
            return false;
        }

        // Ensure number starts with 88
        $number = ltrim($number, '+');
        if (strpos($number, '88') !== 0) {
            $number = '88' . $number;
        }

        try {
            if ($active_provider == 'bulksmsbd') {
                return sendBulkSmsBD($number, $message);
            } elseif ($active_provider == 'nexmo') {
                return sendNexmo($number, $message);
            } elseif ($active_provider == 'twillo') {
                return sendTwillo($number, $message);
            } elseif ($active_provider == 'ssl_wireless') {
                return sendSSLWireless($number, $message, $template_id);
            } elseif ($active_provider == 'fast2sms') {
                return sendFast2SMS($number, $message, $template_id);
            } elseif ($active_provider == 'mimo') {
                return sendMIMO($number, $message);
            }
        } catch (\Exception $e) {
            \Log::error('SMS sending failed: ' . $e->getMessage());
            return false;
        }

        return false;
    }
}

if (!function_exists('sendBulkSmsBD')) {
    function sendBulkSmsBD($number, $message)
    {
        $api_key = env('BULKSMSBD_API_KEY');
        $sender_id = env('BULKSMSBD_SENDER_ID');

        if (empty($api_key) || empty($sender_id)) {
            \Log::error('BulkSMSBD credentials not configured');
            return false;
        }

        $url = "https://bulksmsbd.net/api/smsapi";
        $data = [
            "api_key"  => $api_key,
            "senderid" => $sender_id,
            "number"   => $number,
            "message"  => $message,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

if (!function_exists('sendNexmo')) {
    function sendNexmo($number, $message)
    {
        $nexmo_key = env('NEXMO_KEY');
        $nexmo_secret = env('NEXMO_SECRET');

        $number = ltrim($number, '+');

        $url = "https://rest.nexmo.com/sms/json?" . http_build_query([
            'api_key'    => $nexmo_key,
            'api_secret' => $nexmo_secret,
            'to'         => $number,
            'from'       => env('APP_NAME', 'Enterprise'),
            'text'       => $message,
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

if (!function_exists('sendTwillo')) {
    function sendTwillo($number, $message)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('VALID_TWILLO_NUMBER');

        $number = ltrim($number, '+');

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'To'   => '+' . $number,
            'From' => $from,
            'Body' => $message,
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "{$sid}:{$token}");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

if (!function_exists('sendSSLWireless')) {
    function sendSSLWireless($number, $message, $template_id = '')
    {
        $token = env('SSL_SMS_API_TOKEN');
        $sid = env('SSL_SMS_SID');
        $url = env('SSL_SMS_URL', 'https://smsapi.sslwireless.com');

        $number = ltrim($number, '+');

        $data = [
            'token'       => $token,
            'sid'         => $sid,
            'msisdn'      => $number,
            'sms'         => $message,
            'msg_id'      => $template_id,
            'user_id'     => '',
            'route_id'    => '',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

if (!function_exists('sendFast2SMS')) {
    function sendFast2SMS($number, $message, $template_id = '')
    {
        $auth_key = env('AUTH_KEY');
        $sender_id = env('SENDER_ID');
        $route = env('ROUTE', 'p');

        $number = ltrim($number, '88');

        $url = "https://www.fast2sms.com/dev/bulkV2";

        $data = [
            'authorization' => $auth_key,
            'sender_id'     => $sender_id,
            'message'       => $message,
            'variables_values' => $message,
            'route'         => $route,
            'numbers'       => $number,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'authorization:' . $auth_key,
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

if (!function_exists('sendMIMO')) {
    function sendMIMO($number, $message)
    {
        $username = env('MIMO_USERNAME');
        $password = env('MIMO_PASSWORD');
        $sender_id = env('MIMO_SENDER_ID');

        $number = ltrim($number, '+');

        $url = "https://smsapi.telemong.com/api/sms/send?" . http_build_query([
            'username' => $username,
            'password' => $password,
            'from'     => $sender_id,
            'to'       => $number,
            'text'     => $message,
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}

if (!function_exists('addon_is_activated')) {
    function addon_is_activated($addon_identifier)
    {
        $addon = \App\Models\Addon::where('identifier', $addon_identifier)->first();
        return $addon && $addon->activated == 1;
    }
}

//for api
if (!function_exists('get_images_path')) {
    function get_images_path($given_ids, $with_trashed = false)
    {
        $paths = [];
        $images = get_images($given_ids, $with_trashed);
        if (!$images->isEmpty()) {
            foreach ($images as $image) {
                $paths[] = !is_null($image) ? $image->file_name : "";
            }
        }

        return $paths;
    }
}
