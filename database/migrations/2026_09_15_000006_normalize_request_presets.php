<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $typeMappings = [
            'Property Management' => 'General Repairs',
            'Civil / Carpentry Works' => 'Carpentry & Woodworks',
            'Air-conditioning' => 'Airconditioning',
            'Safety Inspection' => 'General Repairs',
            'Equipment Repair' => 'General Repairs',
            'General Maintenance' => 'General Repairs',
        ];

        foreach ($typeMappings as $oldType => $newType) {
            DB::table('property_requests')->where('request_type', $oldType)->update(['request_type' => $newType]);
        }

        DB::table('property_requests')->where('priority', 'high')->update(['priority' => 'urgent']);
        DB::table('property_requests')->where('priority', 'low')->update(['priority' => 'regular']);
    }

    public function down(): void
    {
        // The legacy categories merged into General Repairs cannot be restored reliably.
    }
};
