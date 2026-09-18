<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('audit_logs')
            ->where('description', 'like', '%Service Order%')
            ->update([
                'description' => DB::raw("REPLACE(description, 'Service Order', 'Service Report')"),
            ]);
    }

    public function down(): void
    {
        DB::table('audit_logs')
            ->where('description', 'like', '%Service Report%')
            ->update([
                'description' => DB::raw("REPLACE(description, 'Service Report', 'Service Order')"),
            ]);
    }
};
