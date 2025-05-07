<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemUnitDetail;
use App\Models\Tax;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ItemAndItemUnitDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param int $count Number of item and item unit detail records to create
     * @return void
     */
    public function run($count = 50)
    {
        $faker = Faker::create();
        $categories = Category::all();
        $units = Unit::all();
        $taxes = Tax::all();

        if ($categories->isEmpty() || $units->isEmpty()) {
            throw new \Exception('Please seed Categories and Units before running this seeder.');
        }

        // Generate the specified number of item and item unit detail records
        for ($i = 0; $i < $count; $i++) {
            $name = $faker->word . ' ' . $faker->randomElement(['Widget', 'Gadget', 'Tool', 'Device']);
            $purchasePrice = $faker->randomFloat(2, 10, 50);
            $wholesalePrice = $faker->randomFloat(2, $purchasePrice + 5, 60);
            $retailPrice = $faker->randomFloat(2, $wholesalePrice + 5, 100);
            $openingStock = $faker->numberBetween(0, 100);
            $categoryId = $categories->random()->id;
            $unitId = $units->random()->id;
            $taxId = $taxes->isNotEmpty() && $faker->boolean(70) ? $taxes->random()->id : null;

            // Create Item
            $item = Item::create([
                'name' => $name,
                'default_category_id' => $categoryId,
                'default_unit_id' => $unitId,
                'tax_id' => $taxId,
                'purchase_price' => $purchasePrice,
                'wholesale_price' => $wholesalePrice,
                'retail_price' => $retailPrice,
                'opening_stock' => $openingStock,
                'current_stock' => $openingStock,
                'status' => 1,
            ]);

            // Fetch unit and tax for ItemUnitDetail
            $unit = Unit::find($unitId);
            $tax = Tax::find($taxId);

            // Create ItemUnitDetail
            ItemUnitDetail::create([
                'default_item_id' => $item->id,
                'name' => $name,
                'unit_name' => $unit->name,
                'quantity' => '1',
                'tax_percentage' => $tax ? $tax->tax_percentage : 0,
                'wholesale_price' => $wholesalePrice,
                'retail_price' => $retailPrice,
                'stock' => $openingStock,
                'type' => 'primary',
            ]);
        }
    }
}
