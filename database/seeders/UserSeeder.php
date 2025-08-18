<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_role = Role::where('name', '=', 'admin')->first();
        $student_role = Role::where('name', '=', 'student')->first();

        User::create([
            'role_id' => $admin_role->id,
            'name' => 'Adhitama F Admin',
            'phone' => '089781032777'
        ]);

        User::create([
            'role_id' => $student_role->id,
            'name' => 'Adhitama F Student',
            'phone' => '087840052978'
        ]);

        // Create 5 more students for testing
        $students = [
            [
                'name' => 'John Doe',
                'phone' => '081234567890',
                'email' => 'john.doe@example.com',
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '081234567891',
                'email' => 'jane.smith@example.com',
            ],
            [
                'name' => 'Mike Johnson',
                'phone' => '081234567892',
                'email' => 'mike.johnson@example.com',
            ],
            [
                'name' => 'Sarah Wilson',
                'phone' => '081234567893',
                'email' => 'sarah.wilson@example.com',
            ],
            [
                'name' => 'David Brown',
                'phone' => '081234567894',
                'email' => 'david.brown@example.com',
            ],
        ];

        foreach ($students as $student) {
            User::create([
                'role_id' => $student_role->id,
                'name' => $student['name'],
                'phone' => $student['phone'],
                'email' => $student['email'],
            ]);
        }
    }
}
