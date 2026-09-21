<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PMManagerSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::withTrashed()->where('email', 'pm.manager@gateway.ph')->first()
            ?? User::withTrashed()->where('email', 'pmmanager@gateway.com')->where('role', 'pm_manager')->first()
            ?? new User;

        $user->forceFill(
            [
                'email' => 'pm.manager@gateway.ph',
                'name' => 'PM Manager',
                'designation' => 'Property Management Manager',
                'role' => 'pm_manager',
                'is_active' => true,
                'password' => Hash::make(User::DEFAULT_PASSWORD),
                'remember_token' => null,
                'deleted_at' => null,
            ]
        )->save();
    }
}
