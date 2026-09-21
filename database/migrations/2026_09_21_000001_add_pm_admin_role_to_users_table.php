<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->enum('role', ['admin', 'dial_lead', 'pm_support', 'handyman', 'dial_a', 'pm_manager', 'pm_admin', 'representative', 'dealer'])
                ->default('dealer')->change();
        });
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'pm_admin')->update(['role' => 'pm_manager']);

        Schema::table('users', function (Blueprint $table): void {
            $table->enum('role', ['admin', 'dial_lead', 'pm_support', 'handyman', 'dial_a', 'pm_manager', 'representative', 'dealer'])
                ->default('dealer')->change();
        });
    }
};
