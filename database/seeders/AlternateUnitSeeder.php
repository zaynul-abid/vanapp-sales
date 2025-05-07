<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AlternateUnit;
use App\Models\Unit;

class AlternateUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Number of alternate units to create
        $count = 15;

        // Common alternate units with their base units
        $alternateUnits = [
            ['name' => 'Box', 'base_unit' => 'pc'],
            ['name' => 'Carton', 'base_unit' => 'pc'],
            ['name' => 'Dozen', 'base_unit' => 'pc'],
            ['name' => 'Pack', 'base_unit' => 'pc'],
            ['name' => 'Set', 'base_unit' => 'pc'],
            ['name' => 'Bundle', 'base_unit' => 'm'],
            ['name' => 'Pallet', 'base_unit' => 'kg'],
            ['name' => 'Case', 'base_unit' => 'pc'],
            ['name' => 'Gross', 'base_unit' => 'pc'],
            ['name' => 'Ream', 'base_unit' => 'pc'],
            ['name' => 'Roll', 'base_unit' => 'm'],
            ['name' => 'Packet', 'base_unit' => 'g'],
            ['name' => 'Tin', 'base_unit' => 'ml'],
            ['name' => 'Jar', 'base_unit' => 'g'],
            ['name' => 'Can', 'base_unit' => 'ml']
        ];

        // Get all base units first
        $units = Unit::all();

        // Create alternate units
        for ($i = 0; $i < $count; $i++) {
            $altUnit = $alternateUnits[$i % count($alternateUnits)];

            // Find matching base unit
            $baseUnit = $units->firstWhere('name', 'like', '%'.$altUnit['base_unit'].'%');

            AlternateUnit::create([
                'name' => $altUnit['name'],
                'description' => $this->getAlternateUnitDescription($altUnit['name'], $altUnit['base_unit']),
                'status' => true,

            ]);
        }
    }

    protected function getAlternateUnitDescription($altUnit, $baseUnit): string
    {
        $descriptions = [
            "Contains multiple {$baseUnit} units",
            "Alternative packaging in {$altUnit} format",
            "Bulk measurement unit equivalent to several {$baseUnit}",
            "Commercial packaging unit",
            "Wholesale quantity measurement",
            "Grouped unit for easier handling"
        ];

        return "{$altUnit} - " . $descriptions[array_rand($descriptions)];
    }
}
