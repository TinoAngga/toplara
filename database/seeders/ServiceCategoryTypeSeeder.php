<?php

namespace Database\Seeders;

use App\Models\ServiceCategoryType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceCategoryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ServiceCategoryType::create([
            'name' => 'game',
            'slug' => 'game'
        ]);
    }
}
