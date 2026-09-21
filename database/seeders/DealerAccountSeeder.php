<?php

namespace Database\Seeders;

use App\Models\Dealer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DealerAccountSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (Dealer::orderBy('source_no')->get() as $dealer) {
            [$personName, $designation] = $this->resolveDealerAccountIdentity($dealer);
            $email = 'dealer.'.Str::slug($dealer->name).'@gateway.ph';
            $legacyEmail = $dealer->source_no === 1 ? 'dealer@gateway.com' : 'dealer'.$dealer->source_no.'@gateway.com';
            // Rename the seeded account in place to preserve its request ownership.
            $user = User::withTrashed()->where('email', $email)->first()
                ?? User::withTrashed()->where('email', $legacyEmail)->where('role', 'dealer')->where('dealer_id', $dealer->id)->first()
                ?? new User;

            $user->forceFill(
                [
                    'email' => $email,
                    'dealer_id' => $dealer->id,
                    'name' => $personName,
                    'designation' => $designation,
                    'role' => 'dealer',
                    'is_active' => true,
                    'password' => Hash::make(User::DEFAULT_PASSWORD),
                    'remember_token' => null,
                    'deleted_at' => null,
                ]
            )->save();
        }
    }

    private function resolveDealerAccountIdentity(Dealer $dealer): array
    {
        $p1 = $this->parsePointPerson($dealer->point_person_1);
        $p2 = $this->parsePointPerson($dealer->point_person_2);
        $isStockyard = str_contains(strtolower($dealer->name), 'stockyard') || str_contains(strtolower($dealer->name), 'yard');

        if ($p1['title'] === 'Area Head' && !empty($p2['name']) && in_array($p2['raw_title'], ['GM', 'ASM', 'GRM', 'Ops Manager'])) {
            return [$p2['name'], $p2['title']];
        }

        if (!empty($p1['name'])) {
            $title = !empty($p1['title']) ? $p1['title'] : ($isStockyard ? 'Stockyard Supervisor' : 'General Manager');
            return [$p1['name'], $title];
        }

        if (!empty($p2['name'])) {
            $title = !empty($p2['title']) ? $p2['title'] : ($isStockyard ? 'Stockyard Supervisor' : 'General Manager');
            return [$p2['name'], $title];
        }

        return [$dealer->name, $isStockyard ? 'Stockyard Supervisor' : 'Dealer Representative'];
    }

    private function parsePointPerson(?string $str): array
    {
        $str = trim((string)$str);
        if ($str === '') {
            return ['name' => '', 'raw_title' => '', 'title' => ''];
        }

        if (preg_match('/^(.*?)\s*-\s*([^-]+)$/', $str, $matches)) {
            $name = trim($matches[1]);
            $rawTitle = trim($matches[2]);
        } else {
            $name = $str;
            $rawTitle = '';
        }

        $title = match (strtoupper($rawTitle)) {
            'GM' => 'General Manager',
            'ASM' => 'Assistant Sales Manager',
            'GRM' => 'General Relations Manager',
            'AREA HEAD' => 'Area Head',
            'OPS MANAGER' => 'Operations Manager',
            'SERVICE ADMIN SUP.' => 'Service Admin Supervisor',
            'SERVICE ADMIN & CR SUP.' => 'Service Admin & CR Supervisor',
            'SECURITY HEAD' => 'Security Head',
            'TOOL KEEPER' => 'Tool Keeper',
            'UTILITY' => 'Utility',
            'PAINTER' => 'Painter',
            'GUARD' => 'Guard',
            default => $rawTitle,
        };

        return [
            'name' => $name,
            'raw_title' => $rawTitle,
            'title' => $title,
        ];
    }
}
