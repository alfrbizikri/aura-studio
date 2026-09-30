<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::create([
            'name' => 'Aura Studio Lhokseumawe',
            'address' => 'Lhokseumawe, Aceh',
            'phone' => '081234567890',
            'opening_time' => '09:00',
            'closing_time' => '17:00',
            'maps_url' => null,
            'status' => 'active',
        ]);

        Branch::create([
            'name' => 'Aura Studio Banda Aceh',
            'address' => 'Banda Aceh, Aceh',
            'phone' => '081234567891',
            'opening_time' => '09:00',
            'closing_time' => '17:00',
            'maps_url' => null,
            'status' => 'active',
        ]);

        Branch::create([
            'name' => 'Aura Studio Bireuen',
            'address' => 'Bireuen, Aceh',
            'phone' => '081234567892',
            'opening_time' => '09:00',
            'closing_time' => '17:00',
            'maps_url' => null,
            'status' => 'active',
        ]);
    }
}