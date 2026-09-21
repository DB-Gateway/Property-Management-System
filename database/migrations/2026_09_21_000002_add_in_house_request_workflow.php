<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->string('assignment_type', 20)->default('dial_a')->index();
            foreach (['assignment', 'in_house_inspection', 'in_house_work_order', 'in_house_completion'] as $prefix) {
                $table->foreignId($prefix.'_by_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string($prefix.'_by_name')->nullable();
                $table->string($prefix.'_by_role', 30)->nullable();
            }
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('in_house_requested_at')->nullable();
            $table->text('in_house_work_order')->nullable();
            $table->timestamp('in_house_work_order_at')->nullable();
            $table->timestamp('in_house_completed_at')->nullable();
            $table->string('dial_a_status', 30)->nullable();
            $table->timestamp('dial_a_completed_at')->nullable();
        });

        Schema::table('request_attachments', function (Blueprint $table) {
            $table->foreignId('uploaded_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('uploaded_by_name')->nullable();
            $table->string('uploaded_by_role', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('request_attachments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('uploaded_by_id');
            $table->dropColumn(['uploaded_by_name', 'uploaded_by_role']);
        });

        Schema::table('property_requests', function (Blueprint $table) {
            foreach (['assignment', 'in_house_inspection', 'in_house_work_order', 'in_house_completion'] as $prefix) {
                $table->dropConstrainedForeignId($prefix.'_by_id');
                $table->dropColumn([$prefix.'_by_name', $prefix.'_by_role']);
            }
            $table->dropIndex(['assignment_type']);
            $table->dropColumn([
                'assignment_type', 'assigned_at', 'in_house_requested_at', 'in_house_work_order',
                'in_house_work_order_at', 'in_house_completed_at', 'dial_a_status', 'dial_a_completed_at',
            ]);
        });
    }
};
