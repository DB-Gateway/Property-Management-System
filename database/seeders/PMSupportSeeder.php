<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PMSupportSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure the single Dial-A user exists
        $dialA = User::where('email', 'dial.handyman@gateway.ph')->first()
            ?? User::where('email', 'diala@gateway.com')->where('role', 'dial_a')->first()
            ?? new User;
        $dialA->fill(
            [
                'email' => 'dial.handyman@gateway.ph',
                'name' => 'Dial-A',
                'designation' => 'Dial-A / Property Management Support',
                'role' => 'dial_a',
                'is_active' => true,
                'password' => Hash::make('Gateway@2026'),
            ]
        )->save();

        // 2. Re-assign any existing requests to Dial-A
        \App\Models\PropertyRequest::whereNull('assigned_support_id')
            ->orWhereIn('assigned_support_id', function ($query) use ($dialA) {
                $query->select('id')->from('users')->where('id', '!=', $dialA->id)->whereIn('role', ['pm_support', 'dial_lead', 'handyman']);
            })
            ->update(['assigned_support_id' => $dialA->id]);

        // 3. Remove all Dial Lead, Handyman, and Representative accounts
        User::where('id', '!=', $dialA->id)
            ->where(function ($query) {
                $query->whereIn('email', [
                    'diallead@gateway.com',
                    'pmsupport@gateway.com',
                    'diallead.a@gateway.com',
                    'diallead.b@gateway.com',
                    'diallead.c@gateway.com',
                    'diallead.d@gateway.com',
                    'dial.a1@gateway.com',
                    'dial.a2@gateway.com',
                    'dial.b1@gateway.com',
                    'dial.b2@gateway.com',
                    'dial.c1@gateway.com',
                    'dial.c2@gateway.com',
                    'rep1@gateway.com',
                    'rep2@gateway.com',
                    'rep3@gateway.com',
                ])
                ->orWhereIn('role', ['handyman', 'representative', 'dial_lead']);
            })
            ->delete();
    }
}
