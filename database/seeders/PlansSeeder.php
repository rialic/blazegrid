<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Plans;

class PlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plan[Str::random(3)] = Plans::firstOrCreate([
            'pl_plan_name' => 'Básico',
        ]);

        $plan[Str::random(3)] = Plans::firstOrCreate([
            'pl_plan_name' => 'Premium',
            'pl_status' => false
        ]);

        $plan[Str::random(3)] = Plans::firstOrCreate([
            'pl_plan_name' => 'Deluxe',
            'pl_status' => false
        ]);
    }
}
