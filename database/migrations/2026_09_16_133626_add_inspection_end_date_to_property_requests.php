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
            $table->date('inspection_end_date')->nullable()->after('inspection_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->dropColumn('inspection_end_date');
        });
    }
};
