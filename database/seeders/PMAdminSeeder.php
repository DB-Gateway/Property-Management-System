<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class PMAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Re-running this seeder must preserve an existing account's password and settings.
        User::withTrashed()->firstOrCreate(['email' => 'pm.admin@gateway.ph'], [
            'name' => 'PM Admin',
            'designation' => 'Property Management Admin',
            'role' => 'pm_admin',
            'is_active' => true,
            'password' => User::DEFAULT_PASSWORD,
            'must_change_password' => true,
        ]);
    }
}
