<?php

namespace App\Services;

use App\Models\SmsTemplate;

class SmsService
{
    public static function phone_number_verification($user = '')
    {
        $sms_template = SmsTemplate::where('identifier', 'phone_number_verification')->first();
        if (!$sms_template) return false;

        $sms_body = $sms_template->sms_body;
        $sms_body = str_replace('[[code]]', $user->verification_code, $sms_body);
        $sms_body = str_replace('[[site_name]]', env('APP_NAME'), $sms_body);

        try {
            sendSMS($user->phone, env('APP_NAME'), $sms_body, $sms_template->template_id);
        } catch (\Exception $e) {
            \Log::error('SMS phone_number_verification failed: ' . $e->getMessage());
        }
    }

    public static function password_reset($user = '')
    {
        $sms_template = SmsTemplate::where('identifier', 'password_reset')->first();
        if (!$sms_template) return false;

        $sms_body = $sms_template->sms_body;
        $sms_body = str_replace('[[code]]', $user->verification_code, $sms_body);
        $sms_body = str_replace('[[site_name]]', env('APP_NAME', 'Enterprise'), $sms_body);

        try {
            sendSMS($user->phone, env('APP_NAME'), $sms_body, $sms_template->template_id);
        } catch (\Exception $e) {
            \Log::error('SMS password_reset failed: ' . $e->getMessage());
        }
    }

    public static function order_placement($phone = '', $order = '')
    {
        $sms_template = SmsTemplate::where('identifier', 'order_placement')->first();
        if (!$sms_template) return false;

        $sms_body = $sms_template->sms_body;
        $sms_body = str_replace('[[order_code]]', $order->code, $sms_body);

        try {
            sendSMS($phone, env('APP_NAME'), $sms_body, $sms_template->template_id);
        } catch (\Exception $e) {
            \Log::error('SMS order_placement failed: ' . $e->getMessage());
        }
    }

    public static function delivery_status_change($phone = '', $order = '')
    {
        $sms_template = SmsTemplate::where('identifier', 'delivery_status_change')->first();
        if (!$sms_template) return false;

        $sms_body = $sms_template->sms_body;
        $sms_body = str_replace('[[order_code]]', $order->code, $sms_body);
        $sms_body = str_replace('[[delivery_status]]', $order->delivery_status, $sms_body);

        try {
            sendSMS($phone, env('APP_NAME'), $sms_body, $sms_template->template_id);
        } catch (\Exception $e) {
            \Log::error('SMS delivery_status_change failed: ' . $e->getMessage());
        }
    }

    public static function payment_status_change($phone = '', $order = '')
    {
        $sms_template = SmsTemplate::where('identifier', 'payment_status_change')->first();
        if (!$sms_template) return false;

        $sms_body = $sms_template->sms_body;
        $sms_body = str_replace('[[payment_status]]', $order->payment_status, $sms_body);
        $sms_body = str_replace('[[order_code]]', $order->code, $sms_body);

        try {
            sendSMS($phone, env('APP_NAME'), $sms_body, $sms_template->template_id);
        } catch (\Exception $e) {
            \Log::error('SMS payment_status_change failed: ' . $e->getMessage());
        }
    }

}
