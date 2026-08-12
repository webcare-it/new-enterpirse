<?php

namespace App\Services;

use Cache;

class CacheService
{
    /**
     * Clear all application caches
     */
    public static function clearAll()
    {
        Cache::flush();
    }

    /**
     * Clear specific cache tags
     */
    public static function clearTags($tags)
    {
        if (is_array($tags)) {
            Cache::tags($tags)->flush();
        } else {
            Cache::tags([$tags])->flush();
        }
    }

    /**
     * Clear product-related caches
     */
    public static function clearProductCache()
    {
        // Clear product-related cache keys
        $keys = [
            'app.products_latest',
            'app.products_admin',
            'app.featured_products',
            'app.best_selling_products',
            'app.products_home_en_newest',
            'app.products_home_en_oldest',
            'app.products_home_en_price_low_to_high',
            'app.products_home_en_price_high_to_low'
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Clear category-related caches
     */
    public static function clearCategoryCache()
    {
        // Clear category-related cache keys
        $keys = [
            'app.categories_0_en',
            'app.featured_categories_en',
            'app.home_categories_en',
            'app.top_categories_en'
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }


    /**
     * Clear settings-related caches
     */
    public static function clearSettingsCache()
    {
        $keys = [
            'app.business_settings',
            'app.general_settings',
            'app.settings'
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Clear policy-related caches
     */
    public static function clearPolicyCache()
    {
        $keys = [
            'app.seller_policy',
            'app.support_policy',
            'app.return_policy'
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Clear page-related caches
     */
    public static function clearPageCache()
    {
        $keys = [
            'app.pages'
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Preload common caches to improve initial load times
     */
    public static function preloadCommonCaches()
    {
        // Preload home page data
        // This would typically be called during deployment or maintenance
    }
}
