<?php

namespace Database\Seeders;

use App\Models\Dealer;
use Illuminate\Database\Seeder;

class DealerSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/dealers.json');
        $dealers = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);

        foreach ($dealers as $dealer) {
            Dealer::updateOrCreate(['source_no' => $dealer['source_no']], $dealer);
        }
    }
}
