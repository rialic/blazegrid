<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        Plans::firstOrCreate(['pl_plan_name' => 'Basic']);

        Plans::firstOrCreate(['pl_plan_name' => 'Premium']);

        Plans::firstOrCreate(['pl_plan_name' => 'Deluxe']);
    }
}
