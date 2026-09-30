<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BranchSeeder::class,
            ServiceSeeder::class,
            BranchServiceSeeder::class,
            PackageSeeder::class,
            PhotographerSeeder::class,
            StudioRoomSeeder::class,
            UserSeeder::class,
            ScheduleSeeder::class,
        ]);
    }
}