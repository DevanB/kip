<?php

namespace Database\Seeders;

use App\Models\KpiTarget;
use Illuminate\Database\Seeder;

class KpiTargetSeeder extends Seeder
{
    public function run(): void
    {
        KpiTarget::updateOrCreate(
            ['kpi_type' => 'rto'],
            ['target_minutes' => 60]
        );

        KpiTarget::updateOrCreate(
            ['kpi_type' => 'rpo'],
            ['target_minutes' => 60]
        );
    }
}
