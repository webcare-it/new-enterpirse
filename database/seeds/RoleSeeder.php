<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default roles for boilerplate
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'Staff'],
            ['name' => 'Customer'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
