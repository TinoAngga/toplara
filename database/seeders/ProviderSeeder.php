<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provider::create([
            'name' => 'MANUAL',
            'api_username' => null,
            'api_key' => null,
            'api_additional' => null,
            'api_url_order' => null,
            'api_url_status' => null,
            'api_url_service' => null,
            'api_url_profile' => null,
            'api_balance' => 0,
            'api_balance_alert' => 10000,
            'is_auto_update' => 1,
            'is_manual' => 1,
            'is_active' => 1
        ]);
        Provider::create([
            'name' => 'VIP-GAME',
            'api_username' => 'uexJ37Q8',
            'api_key' => 'zWNaE5Wdc7IvUz7MmA3r2sklS2Ci836m7CDMZylsbbkiLvuJuRzTHVKolUbt0sHk',
            'api_additional' => '',
            'api_url_order' => 'https://vip-reseller.co.id/api/game-feature',
            'api_url_status' => 'https://vip-reseller.co.id/api/game-feature',
            'api_url_service' => 'https://vip-reseller.co.id/api/game-feature',
            'api_url_profile' => 'https://vip-reseller.co.id/api/game-feature',
            'api_balance' => 0,
            'api_balance_alert' => 10000,
            'is_auto_update' => 1,
            'is_manual' => 0,
            'is_active' => 1
        ]);
    }
}
