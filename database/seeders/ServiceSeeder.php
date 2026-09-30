<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::create([
            'name' => 'Photo Studio',
            'slug' => 'photo-studio',
            'description' => 'Layanan pemotretan di studio dengan fotografer profesional.',
            'image' => null,
            'requires_photographer' => true,
            'requires_room' => true,
            'is_on_location' => false,
            'status' => 'active',
        ]);

        Service::create([
            'name' => 'Self Photo Studio',
            'slug' => 'self-photo-studio',
            'description' => 'Layanan foto mandiri di studio tanpa fotografer.',
            'image' => null,
            'requires_photographer' => false,
            'requires_room' => true,
            'is_on_location' => false,
            'status' => 'active',
        ]);

        Service::create([
            'name' => 'Photography On Location',
            'slug' => 'photography-on-location',
            'description' => 'Layanan pemotretan di lokasi yang ditentukan oleh customer.',
            'image' => null,
            'requires_photographer' => true,
            'requires_room' => false,
            'is_on_location' => true,
            'status' => 'active',
        ]);
    }
}