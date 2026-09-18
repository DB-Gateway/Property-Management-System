<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin', 'pm_support', 'pm_manager', 'dealer') NOT NULL DEFAULT 'dealer'");
        }
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'pm_manager')->update(['role' => 'pm_support']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin', 'pm_support', 'dealer') NOT NULL DEFAULT 'dealer'");
        }
    }
};
