<?php

namespace App\Http\Controllers;

use App\Models\DrTest;
use App\Models\KpiTarget;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $latestTest = DrTest::query()
            ->orderByDesc('test_date')
            ->first();

        $allTests = DrTest::query()
            ->orderBy('test_date')
            ->get(['test_date', 'rto_minutes', 'rpo_minutes'])
            ->map(fn (DrTest $test) => [
                'test_date' => $test->test_date->format('Y-m-d'),
                'rto_minutes' => $test->rto_minutes,
                'rpo_minutes' => $test->rpo_minutes,
            ])
            ->values()
            ->toArray();

        $rtoTarget = KpiTarget::query()
            ->where('kpi_type', 'rto')
            ->value('target_minutes') ?? 60;

        $rpoTarget = KpiTarget::query()
            ->where('kpi_type', 'rpo')
            ->value('target_minutes') ?? 60;

        return Inertia::render('dashboard', [
            'latestRto' => $latestTest?->rto_minutes,
            'latestRpo' => $latestTest?->rpo_minutes,
            'targetRto' => $rtoTarget,
            'targetRpo' => $rpoTarget,
            'testData' => $allTests,
        ]);
    }
}
