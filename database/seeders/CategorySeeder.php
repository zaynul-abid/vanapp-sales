<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Fruits' => 'Fresh and delicious fruits from local and international sources.',
            'Soft Drinks' => 'A variety of carbonated beverages, juices, and refreshments.',
            'Vegetables' => 'Organic and conventional vegetables for healthy cooking.',
            'Dairy' => 'Milk, cheese, yogurt, and other dairy products.',
            'Bakery' => 'Freshly baked bread, pastries, cakes, and desserts.',
            'Snacks' => 'Chips, nuts, cookies, and other quick snack options.',
            'Frozen Foods' => 'Frozen meals, vegetables, and ready-to-cook items.',
            'Meat' => 'Fresh and processed meats including beef, poultry, and pork.',
            'Seafood' => 'Fish, shrimp, and other seafood selections.',
            'Beverages' => 'Hot and cold drinks including coffee, tea, and energy drinks.',
        ];

        foreach ($categories as $name => $description) {
            Category::create([
                'name' => $name,
                'description' => $description, // Added description field
                'status' => rand(0, 1), // Randomly Active or Inactive
            ]);
        }
    }
}
