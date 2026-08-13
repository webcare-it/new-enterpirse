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
        // Dynamically resolve from live request — works across all domains with zero config changes
        $baseUrl  = rtrim(request()->getSchemeAndHttpHost(), '/');
        $appName  = get_setting('site_name') ?: config('app.name');
        $imageUrl = $baseUrl . '/public/';

        // Global SEO fallbacks from admin panel (appearance settings)
        $globalTitle       = get_setting('meta_title')       ?: $appName;
        $globalDescription = get_setting('meta_description') ?: 'Welcome to ' . $appName . '';
        $globalMetaImage   = get_setting('meta_image')       ? uploaded_asset(get_setting('meta_image')) : null;
        $globalOgImage     = $globalMetaImage ? $globalMetaImage : ($baseUrl . '/assets/img/logo.png');
        $globalFavicon     = get_setting('site_icon')        ? uploaded_asset(get_setting('site_icon'))  : null;

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

        // Build meta tag block
        $faviconTag = $meta['favicon']
            ? '<link rel="icon" type="image/png" href="' . htmlspecialchars($meta['favicon']) . '" />'
            : '';

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

        // Remove existing static meta from index.html and inject dynamic ones
        $html = preg_replace('/<title>.*?<\/title>/s', '', $html);
        $html = preg_replace('/<meta\s+name="description"[^>]*>/i', '', $html);
        $html = preg_replace('/<meta\s+name="keywords"[^>]*>/i', '', $html);
        $html = preg_replace('/<meta\s+property="og:[^"]*"[^>]*>/i', '', $html);
        $html = preg_replace('/<meta\s+name="twitter:[^"]*"[^>]*>/i', '', $html);
        $html = preg_replace('/<link\s+rel="canonical"[^>]*>/i', '', $html);
        $html = preg_replace('/<link\s+rel="icon"[^>]*>/i', '', $html);
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
        if (($asset = \App\Models\Upload::find($id)) != null) {
            return my_asset($asset->file_name);
        }
        return null;
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
