<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tax;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Number of tax records to create - adjustable
        $count = 10;

        // Common tax names and base percentages
        $taxTypes = [
            ['name' => 'Standard VAT', 'base_percentage' => 20.0],
            ['name' => 'Reduced VAT', 'base_percentage' => 10.0],
            ['name' => 'Super Reduced VAT', 'base_percentage' => 5.0],
            ['name' => 'Zero Rate', 'base_percentage' => 0.0],
            ['name' => 'Sales Tax', 'base_percentage' => 7.5],
            ['name' => 'GST', 'base_percentage' => 15.0],
            ['name' => 'Service Tax', 'base_percentage' => 12.5],
            ['name' => 'Import Duty', 'base_percentage' => 8.0],
            ['name' => 'Luxury Tax', 'base_percentage' => 25.0],
            ['name' => 'Green Tax', 'base_percentage' => 3.0],
        ];

        // Create tax records
        for ($i = 0; $i < $count; $i++) {
            $baseTax = $taxTypes[$i % count($taxTypes)];
            $variation = $i > count($taxTypes) ? ($i * 0.5) : 0;

            Tax::create([
                'name' => $baseTax['name'] . ($i > count($taxTypes) ? ' ' . ($i + 1) : ''),
                'tax_percentage' => $baseTax['base_percentage'] + $variation,
                'description' => $this->generateTaxDescription($baseTax['name'], $baseTax['base_percentage'] + $variation),
            ]);
        }
    }

    /**
     * Generate descriptive text for taxes
     */
    protected function generateTaxDescription($name, $percentage): string
    {
        $descriptions = [
            "Standard $percentage% $name rate applied to most goods and services",
            "Reduced $percentage% rate for specific categories",
            "Special $percentage% tax rate for this category",
            "Tax exemption (0%) for essential items",
            "$percentage% tax on imported goods",
            "Regional tax rate of $percentage%",
            "Temporary $percentage% rate effective until further notice"
        ];

        return $descriptions[array_rand($descriptions)];
    }
}
