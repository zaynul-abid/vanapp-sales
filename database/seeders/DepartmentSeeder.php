<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Number of departments to create - you can change this
        $count = 10;

        // Array of sample department names
        $departmentNames = [
            'Human Resources',
            'Information Technology',
            'Finance',
            'Marketing',
            'Sales',
            'Operations',
            'Customer Support',
            'Research and Development',
            'Quality Assurance',
            'Administration',
            'Legal',
            'Public Relations',
            'Product Management',
            'Engineering',
            'Design'
        ];

        // Shuffle the array to get random names
        shuffle($departmentNames);

        // Create departments
        for ($i = 0; $i < $count; $i++) {
            $name = $departmentNames[$i % count($departmentNames)] . ($i > count($departmentNames) ? ' ' . ($i + 1) : '');

            Department::create([
                'name' => $name,
                'description' => 'This is the ' . $name . ' department.',
                'status' => 1 // Random status (0 or 1)
            ]);
        }
    }
}
