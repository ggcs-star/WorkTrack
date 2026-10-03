<?php

namespace Database\Seeders;

use App\Models\EmployeeProfile;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'employee']);
        Role::firstOrCreate(['name' => 'hr']);
        Role::firstOrCreate(['name' => 'manager']);
        Role::firstOrCreate(['name' => 'team leader']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@worktrack.test'],
            [
                'name' => 'WorkTrack Admin',
                'password' => Hash::make('Admin@12345'),
                'email_verified_at' => now(),
            ]
        );

        if (! $admin->hasRole('admin')) {
            $admin->assignRole($adminRole);
        }

        $secondAdmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        if (! $secondAdmin->hasRole('admin')) {
            $secondAdmin->assignRole($adminRole);
        }

        $manager = User::firstOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        if (! $manager->hasRole('manager')) {
            $manager->assignRole('manager');
        }

        $this->seedDemoEmployees($admin);
    }

    private function seedDemoEmployees(User $admin): void
    {
        $employees = [
            [
                'name' => 'Karan Verma',
                'email' => 'karan.it@worktrack.test',
                'department' => 'IT',
                'designation' => 'Software Engineer',
                'tasks' => [
                    ['title' => 'Server Maintenance', 'description' => 'Perform routine maintenance and patch updates on production servers.', 'priority' => 'high', 'due_in_days' => 5],
                    ['title' => 'Update Software Licenses', 'description' => 'Renew and update expiring software licenses across the IT infrastructure.', 'priority' => 'medium', 'due_in_days' => 12],
                ],
            ],
            [
                'name' => 'Priya Singh',
                'email' => 'employee@gmail.com',
                'password' => '12345678',
                'department' => 'HR',
                'designation' => 'HR Executive',
                'tasks' => [
                    ['title' => 'Conduct Interview - Backend Developer', 'description' => 'Screen and interview shortlisted candidates for the backend developer role.', 'priority' => 'high', 'due_in_days' => 4],
                    ['title' => 'Update Employee Handbook', 'description' => 'Revise the employee handbook with the latest leave and WFH policies.', 'priority' => 'low', 'due_in_days' => 20],
                ],
            ],
            [
                'name' => 'Amit Patel',
                'email' => 'amit.accounts@worktrack.test',
                'department' => 'Accounts',
                'designation' => 'Accountant',
                'tasks' => [
                    ['title' => 'Process Monthly Invoices', 'description' => 'Review and process all vendor invoices for the current month.', 'priority' => 'high', 'due_in_days' => 7],
                    ['title' => 'Prepare Tax Report', 'description' => 'Compile the quarterly tax report for management review.', 'priority' => 'medium', 'due_in_days' => 15],
                ],
            ],
            [
                'name' => 'Neha Gupta',
                'email' => 'neha.sales@worktrack.test',
                'department' => 'Sales',
                'designation' => 'Sales Executive',
                'tasks' => [
                    ['title' => 'Follow Up With Leads', 'description' => 'Call and follow up with leads generated from the last marketing campaign.', 'priority' => 'medium', 'due_in_days' => 3],
                    ['title' => 'Prepare Sales Presentation', 'description' => 'Put together a presentation for the upcoming client pitch.', 'priority' => 'high', 'due_in_days' => 9],
                ],
            ],
            [
                'name' => 'Vikram Rao',
                'email' => 'vikram.marketing@worktrack.test',
                'department' => 'Marketing',
                'designation' => 'Marketing Specialist',
                'tasks' => [
                    ['title' => 'Launch Social Media Campaign', 'description' => 'Plan and launch the new product social media campaign.', 'priority' => 'high', 'due_in_days' => 6],
                    ['title' => 'Design Promotional Banner', 'description' => 'Create banner artwork for the upcoming seasonal sale.', 'priority' => 'low', 'due_in_days' => 18],
                ],
            ],
        ];

        foreach ($employees as $data) {
            $employee = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password'] ?? 'Employee@123'),
                    'email_verified_at' => now(),
                ]
            );

            if (! $employee->hasRole('employee')) {
                $employee->assignRole('employee');
            }

            EmployeeProfile::updateOrCreate(
                ['user_id' => $employee->id],
                [
                    'department' => $data['department'],
                    'designation' => $data['designation'],
                    'employment_type' => 'Full-time',
                    'joining_date' => now()->subMonths(rand(2, 24)),
                ]
            );

            foreach ($data['tasks'] as $taskData) {
                Task::firstOrCreate(
                    ['title' => $taskData['title'], 'assigned_to' => $employee->id],
                    [
                        'description' => $taskData['description'],
                        'assigned_by' => $admin->id,
                        'due_date' => now()->addDays($taskData['due_in_days']),
                        'priority' => $taskData['priority'],
                        'status' => 'pending',
                    ]
                );
            }
        }
    }
}
