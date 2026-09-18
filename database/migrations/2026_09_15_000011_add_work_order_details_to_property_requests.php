<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->date('work_order_start_date')->nullable()->after('inspection_completed_at');
            $table->json('work_order_representatives')->nullable()->after('work_order_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->dropColumn(['work_order_start_date', 'work_order_representatives']);
        });
    }
};
