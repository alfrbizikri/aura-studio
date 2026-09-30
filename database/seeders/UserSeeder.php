<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Aura Studio',
            'email' => 'admin@aurastudio.com',
            'phone' => '081234567800',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'profile_image' => null,
        ]);

        User::create([
            'name' => 'Customer Demo',
            'email' => 'customer@aurastudio.com',
            'phone' => '081234567801',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'profile_image' => null,
        ]);
    }
}