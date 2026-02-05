<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Create users for each role
        // 1. Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@eduaid.gov',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'phone' => '+63 912 345 6789',
            'address' => '123 Government Building, Manila',
        ]);

        // 2. Scholarship Admin/Staff
        User::create([
            'name' => 'Scholarship Staff',
            'email' => 'staff@eduaid.gov',
            'password' => bcrypt('password'),
            'role_id' => 2,
            'phone' => '+63 912 345 6790',
            'address' => '123 Government Building, Manila',
        ]);

        // 3. Screening Committee
        User::create([
            'name' => 'Screening Committee Member',
            'email' => 'committee@eduaid.gov',
            'password' => bcrypt('password'),
            'role_id' => 3,
            'phone' => '+63 912 345 6791',
            'address' => '123 Government Building, Manila',
        ]);

        // 4. Accounting Officer
        User::create([
            'name' => 'Accounting Officer',
            'email' => 'accounting@eduaid.gov',
            'password' => bcrypt('password'),
            'role_id' => 4,
            'phone' => '+63 912 345 6792',
            'address' => '123 Government Building, Manila',
        ]);

        // 5. Applicant / Scholar
        User::create([
            'name' => 'John Doe',
            'email' => 'applicant@eduaid.gov',
            'password' => bcrypt('password'),
            'role_id' => 5,
            'phone' => '+63 912 345 6793',
            'address' => '456 Student Street, Quezon City',
        ]);

        // 6. Government Official (Viewer)
        User::create([
            'name' => 'Government Official',
            'email' => 'viewer@eduaid.gov',
            'password' => bcrypt('password'),
            'role_id' => 6,
            'phone' => '+63 912 345 6794',
            'address' => '123 Government Building, Manila',
        ]);
    }
}