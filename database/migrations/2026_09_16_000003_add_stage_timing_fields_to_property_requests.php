<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->time('inspection_start_time')->nullable()->after('inspection_date');
            $table->time('inspection_end_time')->nullable()->after('inspection_start_time');
            $table->date('work_order_end_date')->nullable()->after('work_order_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->dropColumn([
                'inspection_start_time',
                'inspection_end_time',
                'work_order_end_date',
            ]);
        });
    }
};
