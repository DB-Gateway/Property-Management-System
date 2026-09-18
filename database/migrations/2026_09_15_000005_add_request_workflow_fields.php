<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->foreignId('assigned_manager_id')->nullable()->after('assigned_support_id')->constrained('users')->nullOnDelete();
            $table->date('approved_date')->nullable()->after('due_date');
            $table->date('inspection_date')->nullable()->after('approved_date');
            $table->string('representative_1')->nullable()->after('inspection_date');
            $table->string('representative_2')->nullable()->after('representative_1');
            $table->string('representative_3')->nullable()->after('representative_2');
            $table->timestamp('inspection_completed_at')->nullable()->after('representative_3');
            $table->timestamp('work_order_completed_at')->nullable()->after('inspection_completed_at');
            $table->timestamp('service_report_completed_at')->nullable()->after('work_order_completed_at');
        });

        Schema::table('request_attachments', function (Blueprint $table) {
            $table->string('category', 30)->default('request')->after('property_request_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('request_attachments', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropColumn('category');
        });

        Schema::table('property_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_manager_id');
            $table->dropColumn([
                'approved_date',
                'inspection_date',
                'representative_1',
                'representative_2',
                'representative_3',
                'inspection_completed_at',
                'work_order_completed_at',
                'service_report_completed_at',
            ]);
        });
    }
};
