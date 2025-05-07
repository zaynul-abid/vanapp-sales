<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Number of units to create
        $count = 12;

        // Common base units
        $baseUnits = [
            'Kilogram (kg)',
            'Gram (g)',
            'Liter (l)',
            'Milliliter (ml)',
            'Meter (m)',
            'Centimeter (cm)',
            'Piece (pc)',
            'Number (no)',
            'Pair',
            'Dozen',
            'Ton',
            'Gallon'
        ];

        // Create units
        for ($i = 0; $i < $count; $i++) {
            Unit::create([
                'name' => $baseUnits[$i],
                'description' => $this->getUnitDescription($baseUnits[$i]),
                'status' => true // All active by default
            ]);
        }
    }

    protected function getUnitDescription($unitName): string
    {
        $descriptions = [
            "Standard unit of measurement",
            "Base measurement unit",
            "Primary unit for quantity calculation",
            "Default measurement unit",
            "Fundamental unit for this product type",
            "Basic unit of measure"
        ];

        return "{$unitName} - " . $descriptions[array_rand($descriptions)];
    }
}
