<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PMManagerSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'pmmanager@gateway.com'],
            [
                'name' => 'PM Manager',
                'designation' => 'Property Management Manager',
                'role' => 'pm_manager',
                'is_active' => true,
                'password' => Hash::make('Gateway@2026'),
            ]
        );
    }
}
