<?php

namespace Database\Seeders;

use App\Models\UserLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('constants.options.member_level') as $key => $value) {
            UserLevel::create([
                'name' => $key,
                'price' => 0,
                'get_balance' => 0,
                'description' => '-'
            ]);
        }
    }
}
