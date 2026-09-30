<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Service;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $photoStudio = Service::where('slug', 'photo-studio')->first();
        $selfPhoto = Service::where('slug', 'self-photo-studio')->first();
        $onLocation = Service::where('slug', 'photography-on-location')->first();

        // PHOTO STUDIO
        Package::create([
            'service_id' => $photoStudio->id,
            'name' => 'Portrait Essentials',
            'description' => 'Paket portrait studio untuk satu orang.',
            'price' => 950000,
            'duration_minutes' => 30,
            'max_people' => 1,
            'photo_count' => null,
            'package_includes' => 'Studio, fotografer, dan hasil foto.',
            'is_active' => true,
        ]);

        Package::create([
            'service_id' => $photoStudio->id,
            'name' => 'Couple Story',
            'description' => 'Paket foto studio untuk pasangan.',
            'price' => 1650000,
            'duration_minutes' => 60,
            'max_people' => 2,
            'photo_count' => null,
            'package_includes' => 'Studio, fotografer, dan hasil foto.',
            'is_active' => true,
        ]);

        Package::create([
            'service_id' => $photoStudio->id,
            'name' => 'Family Moments',
            'description' => 'Paket foto keluarga di studio.',
            'price' => 4950000,
            'duration_minutes' => 90,
            'max_people' => 7,
            'photo_count' => null,
            'package_includes' => 'Studio, fotografer, dan hasil foto.',
            'is_active' => true,
        ]);

        // SELF PHOTO STUDIO
        Package::create([
            'service_id' => $selfPhoto->id,
            'name' => 'Self Mini',
            'description' => 'Paket self photo untuk sesi singkat.',
            'price' => 150000,
            'duration_minutes' => 30,
            'max_people' => 2,
            'photo_count' => null,
            'package_includes' => 'Studio self photo dan hasil foto.',
            'is_active' => true,
        ]);

        Package::create([
            'service_id' => $selfPhoto->id,
            'name' => 'Self Fun',
            'description' => 'Paket self photo untuk grup kecil.',
            'price' => 275000,
            'duration_minutes' => 60,
            'max_people' => 5,
            'photo_count' => null,
            'package_includes' => 'Studio self photo dan hasil foto.',
            'is_active' => true,
        ]);

        Package::create([
            'service_id' => $selfPhoto->id,
            'name' => 'Self Celebration',
            'description' => 'Paket self photo untuk sesi bersama teman atau keluarga.',
            'price' => 450000,
            'duration_minutes' => 90,
            'max_people' => 7,
            'photo_count' => null,
            'package_includes' => 'Studio self photo dan hasil foto.',
            'is_active' => true,
        ]);

        // PHOTOGRAPHY ON LOCATION
        Package::create([
            'service_id' => $onLocation->id,
            'name' => 'Outdoor Portrait',
            'description' => 'Paket portrait di lokasi pilihan customer.',
            'price' => 750000,
            'duration_minutes' => 90,
            'max_people' => null,
            'photo_count' => null,
            'package_includes' => 'Fotografer dan hasil foto.',
            'is_active' => true,
        ]);

        Package::create([
            'service_id' => $onLocation->id,
            'name' => 'Couple Outdoor',
            'description' => 'Paket pemotretan pasangan di lokasi outdoor.',
            'price' => 1200000,
            'duration_minutes' => 240,
            'max_people' => 2,
            'photo_count' => null,
            'package_includes' => 'Fotografer dan hasil foto.',
            'is_active' => true,
        ]);

        Package::create([
            'service_id' => $onLocation->id,
            'name' => 'Event Documentation',
            'description' => 'Paket dokumentasi acara di lokasi customer.',
            'price' => 2000000,
            'duration_minutes' => 480,
            'max_people' => null,
            'photo_count' => null,
            'package_includes' => 'Fotografer dan dokumentasi acara.',
            'is_active' => true,
        ]);
    }
}