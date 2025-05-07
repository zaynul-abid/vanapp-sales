<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class EmployeeAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($count = 50)
    {
        $faker = Faker::create();
        $departments = Department::all();
        $usedEmails = [];

        // Generate the specified number of employee and user records
        for ($i = 0; $i < $count; $i++) {
            // Generate a unique email
            do {
                $email = $faker->safeEmail;
            } while (in_array($email, $usedEmails) || Employee::where('email', $email)->exists() || User::where('email', $email)->exists());
            $usedEmails[] = $email;

            // Generate employee data
            $name = $faker->name;
            $password = Hash::make('password'); // Default password for seeding
            $phone = $faker->phoneNumber;
            $address = $faker->address;
            $position = $faker->randomElement(['Manager', 'Salesperson', 'Driver', 'Clerk', 'Supervisor']);
            $status = $faker->randomElement(['active', 'inactive']);
            $departmentId = $departments->isNotEmpty() && $faker->boolean(70) ? $departments->random()->id : null;

            // Create Employee
            $employee = Employee::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'address' => $address,
                'position' => $position,
                'department_id' => $departmentId,
                'status' => $status,
            ]);

            // Create corresponding User
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'usertype' => 'employee',
                'employee_id' => $employee->id,
            ]);
        }
    }
}
