<?php

namespace Database\Seeders;

use App\Models\Admin\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {

            Customer::create([
                'user_id'     => $user->id,
                'phone'       => $user->phone ?? '01700000000',
                'address'     => 'Dhaka, Bangladesh',
                'country'     => 'Bangladesh',
                'city'        => 'Dhaka',
                'postal_code' => '1200',
                'balance'     => rand(100, 5000),
                'banned'      => 0,
            ]);
        }
    }
}
