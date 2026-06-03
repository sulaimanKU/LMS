<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@lms.com',
            'password' => Hash::make('12345678'), // Password 1 to 8
            'role' => 'admin',
        ]);

        // 2. Create Student User
        User::create([
            'name' => 'Student User',
            'email' => 'student@lms.com',
            'password' => Hash::make('12345678'), // Password 1 to 8
            'role' => 'student',
        ]);

        // 3. Optional: Create Teacher User
        User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@lms.com',
            'password' => Hash::make('12345678'),
            'role' => 'teacher',
        ]);
    }
}
