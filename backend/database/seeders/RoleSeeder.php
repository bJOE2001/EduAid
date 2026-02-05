<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'admin',
                'description' => 'Full system access',
                'permissions' => ['*'],
            ],
            [
                'name' => 'Scholarship Admin/Staff',
                'slug' => 'staff',
                'description' => 'Manage scholarships and applications',
                'permissions' => [
                    'scholarships.create',
                    'scholarships.update',
                    'applications.view',
                    'applications.verify',
                    'applications.approve',
                    'scholars.view',
                    'scholars.update',
                ],
            ],
            [
                'name' => 'Screening Committee',
                'slug' => 'committee',
                'description' => 'Screen and score applications',
                'permissions' => [
                    'screenings.create',
                    'screenings.score',
                    'applications.view',
                ],
            ],
            [
                'name' => 'Accounting Officer',
                'slug' => 'accounting',
                'description' => 'Manage disbursements',
                'permissions' => [
                    'disbursements.create',
                    'disbursements.release',
                    'scholars.view',
                ],
            ],
            [
                'name' => 'Applicant / Scholar',
                'slug' => 'applicant',
                'description' => 'Apply for scholarships and manage profile',
                'permissions' => [
                    'applications.create',
                    'applications.view.own',
                    'profile.update',
                ],
            ],
            [
                'name' => 'Government Official (Viewer)',
                'slug' => 'viewer',
                'description' => 'View reports and statistics',
                'permissions' => [
                    'reports.view',
                    'statistics.view',
                ],
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
