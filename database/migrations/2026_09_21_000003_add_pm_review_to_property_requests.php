<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->timestamp('pm_reviewed_at')->nullable();
            $table->foreignId('pm_reviewed_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('pm_reviewed_by_name')->nullable();
            $table->string('pm_reviewed_by_role', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pm_reviewed_by_id');
            $table->dropColumn(['pm_reviewed_at', 'pm_reviewed_by_name', 'pm_reviewed_by_role']);
        });
    }
};
