<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->string('assignment_phase', 20)->default('proceeded')->index();
            $table->timestamp('assignment_proceeded_at')->nullable();
        });
        DB::table('property_requests')->where('assignment_type', 'pending_review')->update(['assignment_phase' => 'unassigned']);
    }

    public function down(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->dropIndex(['assignment_phase']);
            $table->dropColumn(['assignment_phase', 'assignment_proceeded_at']);
        });
    }
};
