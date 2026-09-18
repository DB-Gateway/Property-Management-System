<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dealers', function (Blueprint $table) {
            $table->string('city', 100)->nullable()->after('address')->index();
        });

        Schema::table('property_requests', function (Blueprint $table) {
            $table->string('dealer_name')->nullable()->after('dealer_id');
        });

        // Populate city in dealers table from dealers.json
        $path = database_path('data/dealers.json');
        if (file_exists($path)) {
            $dealers = json_decode(file_get_contents($path), true);
            if (is_array($dealers)) {
                foreach ($dealers as $d) {
                    if (!empty($d['source_no']) && !empty($d['city'])) {
                        DB::table('dealers')
                            ->where('source_no', $d['source_no'])
                            ->update(['city' => $d['city']]);
                    }
                }
            }
        }

        // Normalize existing property_requests branch to City and dealer_name to brand/name
        $dealersById = DB::table('dealers')->get()->keyBy('id');
        $requests = DB::table('property_requests')->get();
        foreach ($requests as $req) {
            $dealer = $dealersById->get($req->dealer_id);
            $cityName = $dealer?->city ?: $req->branch;
            $dealerBrand = $dealer?->brand ?: ($dealer?->name ?: 'Dealer');
            DB::table('property_requests')
                ->where('id', $req->id)
                ->update([
                    'branch' => $cityName,
                    'dealer_name' => $dealerBrand,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('property_requests', function (Blueprint $table) {
            $table->dropColumn('dealer_name');
        });

        Schema::table('dealers', function (Blueprint $table) {
            $table->dropColumn('city');
        });
    }
};
