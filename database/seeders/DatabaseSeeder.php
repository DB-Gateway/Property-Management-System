<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(DealerSeeder::class);

        $administrator = User::where('email', 'IT.admin@gateway.ph')->first()
            ?? User::where('email', 'administrator@gateway.com')->where('role', 'admin')->first()
            ?? new User;
        $administrator->fill(
            [
                'email' => 'IT.admin@gateway.ph',
                'name' => 'Administrator',
                'designation' => 'Department Head / System Administrator',
                'role' => 'admin',
                'is_active' => true,
                'password' => Hash::make(User::DEFAULT_PASSWORD),
            ]
        )->save();

        $this->call([
            PMSupportSeeder::class,
            PMManagerSeeder::class,
            PMAdminSeeder::class,
            DealerAccountSeeder::class,
        ]);
    }
}
