<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ShippingCost;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Http\Controllers\Controller;
use App\Services\MailService;
use Artisan;

class BusinessSettingsController extends Controller
{
    public function general_setting(Request $request)
    {
        return view('backend.setup_configurations.general_settings');
    }

    public function activation(Request $request)
    {
        return view('backend.setup_configurations.activation');
    }

    public function social_login(Request $request)
    {
        return view('backend.setup_configurations.social_login');
    }

    public function google_analytics(Request $request)
    {
        return view('backend.setup_configurations.google_analytics');
    }

    public function credentials(Request $request)
    {
        return view('backend.setup_configurations.credentials');
    }

    public function smtp_settings(Request $request)
    {
        return view('backend.setup_configurations.smtp_settings');
    }

    public function testEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            MailService::test_email($request->email);
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            return back();
        }

        flash(translate('An email has been sent.'))->success();
        return back();
    }



    /**
     * Update the API key's for payment methods.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function payment_method_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', $request->payment_method . '_sandbox')->first();
        if ($business_settings != null) {
            if ($request->has($request->payment_method . '_sandbox')) {
                $business_settings->value = 1;
                $business_settings->save();
            } else {
                $business_settings->value = 0;
                $business_settings->save();
            }
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    /**
     * Update the API key's for GOOGLE analytics.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function google_analytics_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'google_analytics')->first();

        if ($request->has('google_analytics')) {
            $business_settings->value = 1;
            $business_settings->save();
        } else {
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function google_recaptcha_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'google_recaptcha')->first();

        if ($request->has('google_recaptcha')) {
            $business_settings->value = 1;
            $business_settings->save();
        } else {
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function google_map_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'google_map')->first();

        if ($request->has('google_map')) {
            $business_settings->value = 1;
            $business_settings->save();
        } else {
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function google_firebase_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'google_firebase')->first();

        if ($request->has('google_firebase')) {
            $business_settings->value = 1;
            $business_settings->save();
        } else {
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }


    /**
     * Update the API key's for GOOGLE analytics.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function facebook_chat_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'facebook_chat')->first();

        if ($request->has('facebook_chat')) {
            $business_settings->value = 1;
            $business_settings->save();
        } else {
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function facebook_comment_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'facebook_comment')->first();
        if (!$business_settings) {
            $business_settings = new BusinessSetting;
            $business_settings->type = 'facebook_comment';
        }

        $business_settings->value = 0;
        if ($request->facebook_comment) {
            $business_settings->value = 1;
        }

        $business_settings->save();

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function facebook_pixel_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        $business_settings = BusinessSetting::where('type', 'facebook_pixel')->first();

        if ($request->has('facebook_pixel')) {
            $business_settings->value = 1;
            $business_settings->save();
        } else {
            $business_settings->value = 0;
            $business_settings->save();
        }

        Artisan::call('cache:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    /**
     * Update the API key's for other methods.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function env_key_update(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }
        Artisan::call('config:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    /**
     * overWrite the Env File values.
     * @param  String type
     * @param  String value
     * @return \Illuminate\Http\Response
     */
    public function overWriteEnvFile($type, $val)
    {
        if (env('DEMO_MODE') != 'On') {
            $path = base_path('.env');
            if (file_exists($path)) {
                $val = '"' . trim($val) . '"';
                $envContent = file_get_contents($path);

                // Use regex to find and replace the key=value pair
                $pattern = '/^' . preg_quote($type, '/') . '=.*$/m';

                if (preg_match($pattern, $envContent)) {
                    // Key exists, replace the entire line
                    $envContent = preg_replace($pattern, $type . '=' . $val, $envContent);
                    file_put_contents($path, $envContent);
                } else {
                    // Key doesn't exist, append it
                    file_put_contents($path, $envContent . "\r\n" . $type . '=' . $val);
                }
            }
        }
        return response()->json(['message' => 'Environment variable updated successfully']);
    }



    public function update(Request $request)
    {
        Artisan::call('optimize:clear');
        // Validate all fields
        $request->validate([
            // Layout breakpoints
            'homePage_mobile'      => 'nullable|integer|min:1|max:6',
            'homePage_tablet'      => 'nullable|integer|min:1|max:6',
            'homePage_laptop'      => 'nullable|integer|min:1|max:6',
            'homePage_desktop'     => 'nullable|integer|min:1|max:6',
            'homePage_ultrawide'   => 'nullable|integer|min:1|max:6',
            'withFilter_mobile'    => 'nullable|integer|min:1|max:6',
            'withFilter_tablet'    => 'nullable|integer|min:1|max:6',
            'withFilter_laptop'    => 'nullable|integer|min:1|max:6',
            'withFilter_desktop'   => 'nullable|integer|min:1|max:6',
            'withFilter_ultrawide' => 'nullable|integer|min:1|max:6',
            'fullLayout_mobile'    => 'nullable|integer|min:1|max:6',
            'fullLayout_tablet'    => 'nullable|integer|min:1|max:6',
            'fullLayout_laptop'    => 'nullable|integer|min:1|max:6',
            'fullLayout_desktop'   => 'nullable|integer|min:1|max:6',
            'fullLayout_ultrawide' => 'nullable|integer|min:1|max:6',

            // Droploo credentials (optional)
            'DROPLOO_APP_KEY'      => 'nullable|string|max:255',
            'DROPLOO_APP_SECRET'   => 'nullable|string|max:255',
            'DROPLOO_USERNAME'     => 'nullable|string|max:255',
        ]);

        foreach ($request->types as $key => $type) {
            // ---- Handle 'layout_breakpoints' as a special case ----
            if ($type == 'layout_breakpoints') {
                $groups = ['homePage', 'withFilter', 'fullLayout'];
                $breakpoints = ['mobile', 'tablet', 'laptop', 'desktop', 'ultrawide'];
                $layout = [];

                foreach ($groups as $group) {
                    foreach ($breakpoints as $bp) {
                        $field = $group . '_' . $bp;
                        $layout[$group][$bp] = (int) $request->input($field, 1);
                    }
                }

                $value = json_encode($layout);
                $business_settings = BusinessSetting::where('type', $type)->first();
                if ($business_settings) {
                    $business_settings->value = $value;
                    $business_settings->save();
                } else {
                    BusinessSetting::create([
                        'type'  => $type,
                        'value' => $value,
                    ]);
                }
                continue;
            }

            // ---- Original logiac for all other types ----
            if ($type == 'timezone') {
                $this->overWriteEnvFile('APP_TIMEZONE', $request[$type]);
            } else {
                $lang = null;
                if (gettype($type) == 'array') {
                    $lang = array_key_first($type);
                    $type = $type[$lang];
                    $business_settings = BusinessSetting::where('type', $type)->first();
                } else {
                    $business_settings = BusinessSetting::where('type', $type)->first();
                }

                $raw = $request[$type];
                $value = is_array($raw) ? $raw : trim($raw);

                // Save to database
                if ($business_settings != null) {
                    $business_settings->value = is_array($value) ? json_encode($value) : $value;
                    $business_settings->lang = $lang;
                    $business_settings->save();
                } else {
                    $business_settings = new BusinessSetting;
                    $business_settings->type = $type;
                    $business_settings->value = is_array($value) ? json_encode($value) : $value;
                    $business_settings->lang = $lang;
                    $business_settings->save();
                }

                // ---- Write Droploo credentials to .env ----
                if (in_array($type, ['DROPLOO_APP_KEY', 'DROPLOO_APP_SECRET', 'DROPLOO_USERNAME'])) {
                    $this->overWriteEnvFile($type, $value);
                }

                // Also handle site_name to .env
                if ($type == 'site_name') {
                    $this->overWriteEnvFile('APP_NAME', $request[$type]);
                }
            }
        }

        Artisan::call('optimize:clear');

        flash(translate("Settings updated successfully"))->success();
        return back();
    }

    public function updateActivationSettings(Request $request)
    {
        $env_changes = ['FORCE_HTTPS', 'FILESYSTEM_DRIVER'];
        if (in_array($request->type, $env_changes)) {

            return $this->updateActivationSettingsInEnv($request);
        }

        $business_settings = BusinessSetting::where('type', $request->type)->first();
        if ($business_settings != null) {
            if ($request->type == 'maintenance_mode' && $request->value == '1') {
                if (env('DEMO_MODE') != 'On') {
                    Artisan::call('down');
                }
            } elseif ($request->type == 'maintenance_mode' && $request->value == '0') {
                if (env('DEMO_MODE') != 'On') {
                    Artisan::call('up');
                }
            }
            $business_settings->value = $request->value;
            $business_settings->save();
        } else {
            $business_settings = new BusinessSetting;
            $business_settings->type = $request->type;
            $business_settings->value = $request->value;
            $business_settings->save();
        }

        Artisan::call('cache:clear');
        return '1';
    }

    public function updateActivationSettingsInEnv($request)
    {
        if ($request->type == 'FORCE_HTTPS' && $request->value == '1') {
            $this->overWriteEnvFile($request->type, 'On');

            if (strpos(env('APP_URL'), 'http:') !== FALSE) {
                $this->overWriteEnvFile('APP_URL', str_replace("http:", "https:", env('APP_URL')));
            }
        } elseif ($request->type == 'FORCE_HTTPS' && $request->value == '0') {
            $this->overWriteEnvFile($request->type, 'Off');
            if (strpos(env('APP_URL'), 'https:') !== FALSE) {
                $this->overWriteEnvFile('APP_URL', str_replace("https:", "http:", env('APP_URL')));
            }
        } elseif ($request->type == 'FILESYSTEM_DRIVER' && $request->value == '1') {
            $this->overWriteEnvFile($request->type, 's3');
        } elseif ($request->type == 'FILESYSTEM_DRIVER' && $request->value == '0') {
            $this->overWriteEnvFile($request->type, 'local');
        }

        return '1';
    }
}
