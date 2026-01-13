<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateKpiTargetRequest;
use App\Models\KpiTarget;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class KpiTargetController extends Controller
{
    public function edit(): Response
    {
        $rtoTarget = KpiTarget::query()->where('kpi_type', 'rto')->value('target_minutes') ?? 60;
        $rpoTarget = KpiTarget::query()->where('kpi_type', 'rpo')->value('target_minutes') ?? 60;

        return Inertia::render('settings/kpi-targets', [
            'rtoTarget' => $rtoTarget,
            'rpoTarget' => $rpoTarget,
        ]);
    }

    public function update(UpdateKpiTargetRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        KpiTarget::updateOrCreate(
            ['kpi_type' => 'rto'],
            ['target_minutes' => $validated['rto_target']]
        );

        KpiTarget::updateOrCreate(
            ['kpi_type' => 'rpo'],
            ['target_minutes' => $validated['rpo_target']]
        );

        return redirect()->route('kpi-targets.edit')->with('success', 'KPI targets updated successfully.');
    }
}
