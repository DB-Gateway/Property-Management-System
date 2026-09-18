<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->time('work_order_start_time')->nullable()->after('work_order_start_date');
            $table->time('work_order_end_time')->nullable()->after('work_order_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->dropColumn(['work_order_start_time', 'work_order_end_time']);
        });
    }
};
