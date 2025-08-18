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
        //
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
    }
}
