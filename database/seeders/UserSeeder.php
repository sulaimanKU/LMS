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
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@lms.com',
            'password' => Hash::make('12345678'), // Password 1 to 8
        ]);
        $admin->assignRole('admin');

        // 2. Create Student User
        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@lms.com',
            'password' => Hash::make('12345678'), // Password 1 to 8
        ]);
        $student->assignRole('student');

        // 3. Optional: Create Teacher User
        $teacher = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@lms.com',
            'password' => Hash::make('12345678'),
        ]);
        $teacher->assignRole('teacher');
    }
}
