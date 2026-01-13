<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDrTestRequest;
use App\Models\DrTest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DrTestController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('dr-tests/create');
    }

    public function store(StoreDrTestRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $drTest = DrTest::create([
            'test_date' => $validated['test_date'],
            'rto_minutes' => $validated['rto_minutes'],
            'rpo_minutes' => $validated['rpo_minutes'],
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['phases'] as $phase) {
            $startedAt = Carbon::parse($phase['started_at']);
            $finishedAt = Carbon::parse($phase['finished_at']);
            $durationMinutes = (int) $startedAt->diffInMinutes($finishedAt);

            $drTest->phases()->create([
                'title' => $phase['title'],
                'started_at' => $startedAt,
                'finished_at' => $finishedAt,
                'duration_minutes' => $durationMinutes,
            ]);
        }

        return redirect()->route('dashboard');
    }
}
