<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class CarSeeding extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Car::factory()->create([
            'model' => 'Myvi',
            'type' => 'hatchback',
            'transmission' => 'AT',
            'brand' => 'testBrand',
            'price_per_day' => '120',
            'is_available' => 'true',
        ]);
    }
}
