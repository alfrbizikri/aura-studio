<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Service;
use Illuminate\Database\Seeder;

class BranchServiceSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $services = Service::all();

        foreach ($branches as $branch) {
            foreach ($services as $service) {
                $branch->services()->attach($service->id, [
                    'status' => 'active',
                ]);
            }
        }
    }
}