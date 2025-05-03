<?php

namespace Database\Seeders;

use App\Models\Van;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(int $count = 28, int $yearRange = 10)
    {
        $faker = Faker::create();

        for ($i = 0; $i < $count; $i++) {
            Van::create([
                'name' => $faker->word . ' Van ' . $faker->numberBetween(100, 999),
                'register_number' => strtoupper($faker->bothify('???###')) . '-' . $faker->numberBetween(100, 999),
                'status' => $faker->boolean(80), // 80% chance of being active
                'model' => $faker->randomElement(['Sprinter', 'Transit', 'ProMaster', 'NV200', 'Econoline']),
                'manufacture_year' => $faker->year(),
                'capacity' => $faker->numberBetween(1000, 5000), // Capacity in kg
                'employee_id' => null,
            ]);
        }
    }
}
