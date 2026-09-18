<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin', 'dial_lead', 'pm_support', 'handyman', 'dial_a', 'pm_manager', 'representative', 'dealer') NOT NULL DEFAULT 'dealer'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin', 'dial_lead', 'pm_support', 'handyman', 'pm_manager', 'representative', 'dealer') NOT NULL DEFAULT 'dealer'");
        }
    }
};
