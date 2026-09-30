<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Service;
use App\Models\Photographer;
use App\Models\StudioRoom;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $photoStudio = Service::where('slug', 'photo-studio')->first();
        $selfPhoto = Service::where('slug', 'self-photo-studio')->first();
        $onLocation = Service::where('slug', 'photography-on-location')->first();

        $branches = Branch::all();

        foreach ($branches as $branch) {

            $photographer = Photographer::where('branch_id', $branch->id)
                ->first();

            $studioRoom = StudioRoom::where('branch_id', $branch->id)
                ->where('name', 'Studio Room A')
                ->first();

            $selfPhotoRoom = StudioRoom::where('branch_id', $branch->id)
                ->where('name', 'Self Photo Room')
                ->first();

            // PHOTO STUDIO
            Schedule::create([
                'branch_id' => $branch->id,
                'service_id' => $photoStudio->id,
                'photographer_id' => $photographer->id,
                'studio_room_id' => $studioRoom->id,
                'schedule_date' => '2026-10-01',
                'start_time' => '09:00',
                'end_time' => '10:00',
                'status' => 'available',
            ]);

            Schedule::create([
                'branch_id' => $branch->id,
                'service_id' => $photoStudio->id,
                'photographer_id' => $photographer->id,
                'studio_room_id' => $studioRoom->id,
                'schedule_date' => '2026-10-01',
                'start_time' => '10:00',
                'end_time' => '11:00',
                'status' => 'available',
            ]);

            // SELF PHOTO STUDIO
            Schedule::create([
                'branch_id' => $branch->id,
                'service_id' => $selfPhoto->id,
                'photographer_id' => null,
                'studio_room_id' => $selfPhotoRoom->id,
                'schedule_date' => '2026-10-01',
                'start_time' => '11:00',
                'end_time' => '12:00',
                'status' => 'available',
            ]);

            // PHOTOGRAPHY ON LOCATION
            Schedule::create([
                'branch_id' => $branch->id,
                'service_id' => $onLocation->id,
                'photographer_id' => $photographer->id,
                'studio_room_id' => null,
                'schedule_date' => '2026-10-01',
                'start_time' => '13:00',
                'end_time' => '14:00',
                'status' => 'available',
            ]);
        }
    }
}