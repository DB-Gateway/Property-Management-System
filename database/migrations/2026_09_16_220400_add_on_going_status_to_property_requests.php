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
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE property_requests MODIFY COLUMN status ENUM('pending', 'on_going', 'in_progress', 'awaiting_dealer', 'completed') NOT NULL DEFAULT 'pending'");
        }
        DB::table('property_requests')->where('status', 'in_progress')->update(['status' => 'on_going']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('property_requests')->where('status', 'on_going')->update(['status' => 'in_progress']);
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE property_requests MODIFY COLUMN status ENUM('pending', 'in_progress', 'awaiting_dealer', 'completed') NOT NULL DEFAULT 'pending'");
        }
    }
};
