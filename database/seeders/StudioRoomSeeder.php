<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\StudioRoom;
use Illuminate\Database\Seeder;

class StudioRoomSeeder extends Seeder
{
    public function run(): void
    {
        $lhokseumawe = Branch::where('name', 'Aura Studio Lhokseumawe')->first();
        $bandaAceh = Branch::where('name', 'Aura Studio Banda Aceh')->first();
        $bireuen = Branch::where('name', 'Aura Studio Bireuen')->first();

        StudioRoom::create([
            'branch_id' => $lhokseumawe->id,
            'name' => 'Studio Room A',
            'description' => 'Ruang studio utama untuk sesi Photo Studio.',
            'status' => 'active',
        ]);

        StudioRoom::create([
            'branch_id' => $lhokseumawe->id,
            'name' => 'Self Photo Room',
            'description' => 'Ruang khusus layanan Self Photo Studio.',
            'status' => 'active',
        ]);

        StudioRoom::create([
            'branch_id' => $bandaAceh->id,
            'name' => 'Studio Room A',
            'description' => 'Ruang studio utama untuk sesi Photo Studio.',
            'status' => 'active',
        ]);

        StudioRoom::create([
            'branch_id' => $bandaAceh->id,
            'name' => 'Self Photo Room',
            'description' => 'Ruang khusus layanan Self Photo Studio.',
            'status' => 'active',
        ]);

        StudioRoom::create([
            'branch_id' => $bireuen->id,
            'name' => 'Studio Room A',
            'description' => 'Ruang studio utama untuk sesi Photo Studio.',
            'status' => 'active',
        ]);

        StudioRoom::create([
            'branch_id' => $bireuen->id,
            'name' => 'Self Photo Room',
            'description' => 'Ruang khusus layanan Self Photo Studio.',
            'status' => 'active',
        ]);
    }
}