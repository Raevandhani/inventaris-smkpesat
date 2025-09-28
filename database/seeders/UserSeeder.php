<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::create([
            'name' => 'Teacher',
            'email' => 'teacher@gmail.com',
            'password' => 'teacher123',
            'is_verified' => true
        ]);
        $teacher->assignRole('teacher');

        $student = User::create([
            'name' => 'Student',
            'email' => 'student@gmail.com',
            'password' => 'student123',
        ]);
        $student->assignRole('student');
    }
}