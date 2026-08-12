<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusinessSetting;

class BusinessSettingSeeder extends Seeder
{
    public function run(): void
    {
        $currencySetting = [
            "currency" => "BDT",
            "currency_symbol" => "৳",
            "is_currency_symbol" => true,
            "currency_position" => "before",
            "is_decimal" => true,
            "decimal_digits" => 2,
        ];

        BusinessSetting::updateOrCreate(
            ['type' => 'currency_setting'],
            [
                'value' => json_encode($currencySetting),
                'lang' => null,
            ]
        );
    }
}
