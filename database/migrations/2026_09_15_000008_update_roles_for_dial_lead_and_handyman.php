<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin', 'dial_lead', 'pm_support', 'handyman', 'pm_manager', 'representative', 'dealer') NOT NULL DEFAULT 'dealer'");
        }

        Schema::table('property_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('property_requests', 'completion_notified_at')) {
                $table->timestamp('completion_notified_at')->nullable()->after('service_report_completed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            if (Schema::hasColumn('property_requests', 'completion_notified_at')) {
                $table->dropColumn('completion_notified_at');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin', 'pm_support', 'pm_manager', 'representative', 'dealer') NOT NULL DEFAULT 'dealer'");
        }
    }
};
