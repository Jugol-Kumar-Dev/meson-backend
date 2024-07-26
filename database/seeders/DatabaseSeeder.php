<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {



        \App\Models\User::create([
            'name' => 'Admin User',
            'phone' => '01516148428',
            'email' => 'admin@admin.com',
            'password' => '12345678',
            'role' => 'admin',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        \App\Models\User::create([
            'name' => 'Student User',
            'phone' => '01500000000',
            'email' => 'student@student.com',
            'password' => '12345678',
            'role' => 'student',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
    }
}
