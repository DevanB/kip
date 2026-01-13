<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDrTestRequest;
use App\Http\Requests\UpdateDrTestRequest;
use App\Models\DrTest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DrTestController extends Controller
{
    public function index(): Response
    {
        $drTests = DrTest::query()
            ->withCount('phases')
            ->orderByDesc('test_date')
            ->get()
            ->map(fn (DrTest $test) => [
                'id' => $test->id,
                'test_date' => $test->test_date->format('Y-m-d'),
                'rto_minutes' => $test->rto_minutes,
                'rpo_minutes' => $test->rpo_minutes,
                'phases_count' => $test->phases_count,
            ]);

        return Inertia::render('dr-tests/index', [
            'drTests' => $drTests,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('dr-tests/create');
    }

    public function show(DrTest $drTest): Response
    {
        $drTest->load('phases');

        return Inertia::render('dr-tests/show', [
            'drTest' => [
                'id' => $drTest->id,
                'test_date' => $drTest->test_date->format('Y-m-d'),
                'rto_minutes' => $drTest->rto_minutes,
                'rpo_minutes' => $drTest->rpo_minutes,
                'notes' => $drTest->notes,
                'phases' => $drTest->phases->map(fn ($phase) => [
                    'id' => $phase->id,
                    'title' => $phase->title,
                    'started_at' => $phase->started_at->format('Y-m-d H:i'),
                    'finished_at' => $phase->finished_at->format('Y-m-d H:i'),
                    'duration_minutes' => $phase->duration_minutes,
                ]),
            ],
        ]);
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

    public function edit(DrTest $drTest): Response
    {
        $drTest->load('phases');

        return Inertia::render('dr-tests/edit', [
            'drTest' => [
                'id' => $drTest->id,
                'test_date' => $drTest->test_date->format('Y-m-d'),
                'rto_minutes' => $drTest->rto_minutes,
                'rpo_minutes' => $drTest->rpo_minutes,
                'notes' => $drTest->notes,
                'phases' => $drTest->phases->map(fn ($phase) => [
                    'id' => $phase->id,
                    'title' => $phase->title,
                    'started_at' => $phase->started_at->format('Y-m-d\TH:i'),
                    'finished_at' => $phase->finished_at->format('Y-m-d\TH:i'),
                ]),
            ],
        ]);
    }

    public function update(UpdateDrTestRequest $request, DrTest $drTest): RedirectResponse
    {
        $validated = $request->validated();

        $drTest->update([
            'test_date' => $validated['test_date'],
            'rto_minutes' => $validated['rto_minutes'],
            'rpo_minutes' => $validated['rpo_minutes'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $drTest->phases()->delete();

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

        return redirect()->route('dr-tests.show', $drTest);
    }
}
