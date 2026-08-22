<?php

namespace App\Utility;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PathaoUtility {
    protected static function base_url()
    {
        return rtrim(get_setting('pathao_base_url', 'https://api-hermes.pathao.com'), '/');
    }

    public static function get_token()
    {
        return Cache::remember('pathao_production_access_token', now()->addMinutes(50), function () {
            $client_id     = get_setting('pathao_client_id');
            $client_secret = get_setting('pathao_client_secret');
            $username      = get_setting('pathao_username');
            $password      = get_setting('pathao_password');

            if (!$client_id || !$client_secret || !$username || !$password) {
                Log::error('Pathao token request skipped because one or more credentials are missing.', [
                    'has_client_id' => (bool) $client_id,
                    'has_client_secret' => (bool) $client_secret,
                    'has_username' => (bool) $username,
                    'has_password' => (bool) $password,
                    'base_url' => self::base_url(),
                ]);
                return null;
            }

            try {
                $response = Http::asJson()->post(self::base_url() . '/aladdin/api/v1/issue-token', [
                    'client_id'     => $client_id,
                    'client_secret' => $client_secret,
                    'grant_type'    => 'password',
                    'username'      => $username,
                    'password'      => $password,
                ]);

                if (!$response->successful()) {
                    Log::error('Pathao token request was rejected.', [
                        'status' => $response->status(),
                        'body' => $response->json() ?: $response->body(),
                        'base_url' => self::base_url(),
                    ]);
                    return null;
                }

                $access_token = $response->json('access_token');

                if (!$access_token) {
                    Log::error('Pathao token response did not contain an access token.', [
                        'status' => $response->status(),
                        'body' => $response->json() ?: $response->body(),
                        'base_url' => self::base_url(),
                    ]);
                }

                return $access_token;
            } catch (\Exception $e) {
                Log::error('Pathao token request failed with an exception.', [
                    'message' => $e->getMessage(),
                    'base_url' => self::base_url(),
                ]);
                return null;
            }
        });
    }

    public static function create_order($payload)
    {
        $token = self::get_token();

        if (!$token) {
            return [
                'status'  => 401,
                'message' => 'Pathao authentication failed. Please check your credentials.'
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ])->post(self::base_url() . '/aladdin/api/v1/orders', $payload);

            return $response->json();
        } catch (\Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Pathao API error: ' . $e->getMessage()
            ];
        }
    }

    public static function get_stores()
    {
        $token = self::get_token();

        if (!$token) {
            return [
                'status'  => 401,
                'message' => 'Pathao authentication failed. Please check your credentials.'
            ];
        }

        try {
            $response = Http::withToken($token)->get(self::base_url() . '/aladdin/api/v1/stores');

            return $response->json();
        } catch (\Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Pathao API error: ' . $e->getMessage()
            ];
        }
    }

    public static function get_tracking_info($consignment_id)
    {
        $token = self::get_token();

        if (!$token) {
            return [
                'status'  => 401,
                'message' => 'Pathao authentication failed. Please check your credentials.'
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type'  => 'application/json',
            ])->get(self::base_url() . '/aladdin/api/v1/orders/' . rawurlencode($consignment_id) . '/info');

            return $response->json();
        } catch (\Exception $e) {
            return [
                'status'  => 500,
                'message' => 'Pathao API error: ' . $e->getMessage()
            ];
        }
    }
}
