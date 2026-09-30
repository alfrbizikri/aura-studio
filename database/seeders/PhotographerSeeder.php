<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Photographer;
use Illuminate\Database\Seeder;

class PhotographerSeeder extends Seeder
{
    public function run(): void
    {
        $lhokseumawe = Branch::where('name', 'Aura Studio Lhokseumawe')->first();
        $bandaAceh = Branch::where('name', 'Aura Studio Banda Aceh')->first();
        $bireuen = Branch::where('name', 'Aura Studio Bireuen')->first();

        Photographer::create([
            'branch_id' => $lhokseumawe->id,
            'name' => 'Nadia Putri',
            'photo' => null,
            'specialization' => 'Portrait & Family',
            'description' => 'Fotografer dengan spesialisasi portrait dan foto keluarga.',
            'status' => 'active',
        ]);

        Photographer::create([
            'branch_id' => $lhokseumawe->id,
            'name' => 'Ricky Mahendra',
            'photo' => null,
            'specialization' => 'Couple & Graduation',
            'description' => 'Fotografer dengan spesialisasi couple dan graduation.',
            'status' => 'active',
        ]);

        Photographer::create([
            'branch_id' => $bandaAceh->id,
            'name' => 'Ilham Ardiano',
            'photo' => null,
            'specialization' => 'Outdoor & Event',
            'description' => 'Fotografer dengan spesialisasi outdoor dan dokumentasi acara.',
            'status' => 'active',
        ]);

        Photographer::create([
            'branch_id' => $bandaAceh->id,
            'name' => 'Alya Rahma',
            'photo' => null,
            'specialization' => 'Portrait',
            'description' => 'Fotografer dengan spesialisasi portrait studio.',
            'status' => 'active',
        ]);

        Photographer::create([
            'branch_id' => $bireuen->id,
            'name' => 'Fajar Maulana',
            'photo' => null,
            'specialization' => 'Family & Event',
            'description' => 'Fotografer dengan spesialisasi keluarga dan dokumentasi event.',
            'status' => 'active',
        ]);

        Photographer::create([
            'branch_id' => $bireuen->id,
            'name' => 'Salsa Nabila',
            'photo' => null,
            'specialization' => 'Couple & Outdoor',
            'description' => 'Fotografer dengan spesialisasi couple dan outdoor.',
            'status' => 'active',
        ]);
    }
}