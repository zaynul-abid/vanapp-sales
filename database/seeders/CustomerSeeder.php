<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Faker\Factory;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing records (optional)
        // Customer::truncate();

        $faker = Factory::create();

        // Set the number of customers to create
        $customerCount = 50;

        // Customer types
        $customerTypes = ['Retail', 'Wholesale', 'Corporate', 'Government'];

        // Create customers
        for ($i = 0; $i < $customerCount; $i++) {
            Customer::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'customer_type' => $faker->randomElement($customerTypes),
                'is_active' => $faker->boolean(90), // 90% chance of being active
            ]);
        }
    }
}
