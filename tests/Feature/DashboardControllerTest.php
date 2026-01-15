<?php

use App\Models\DrTest;
use App\Models\KpiTarget;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

it('renders the dashboard with no test data', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/dashboard')
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('dashboard')
            ->where('latestRto', null)
            ->where('latestRpo', null)
            ->where('targetRto', 60)
            ->where('targetRpo', 60)
            ->where('testData', [])
        );
});

it('renders the dashboard with latest test data', function () {
    $this->actingAs(User::factory()->create());

    DrTest::factory()->create([
        'test_date' => '2026-01-10',
        'rto_minutes' => 45,
        'rpo_minutes' => 30,
    ]);

    DrTest::factory()->create([
        'test_date' => '2026-01-12',
        'rto_minutes' => 55,
        'rpo_minutes' => 25,
    ]);

    $this->get('/dashboard')
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('dashboard')
            ->where('latestRto', 55)
            ->where('latestRpo', 25)
        );
});

it('passes all tests to trend chart sorted by date', function () {
    $this->actingAs(User::factory()->create());

    DrTest::factory()->create([
        'test_date' => '2026-01-12',
        'rto_minutes' => 55,
        'rpo_minutes' => 25,
    ]);

    DrTest::factory()->create([
        'test_date' => '2026-01-10',
        'rto_minutes' => 45,
        'rpo_minutes' => 30,
    ]);

    $this->get('/dashboard')
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('dashboard')
            ->has('testData', 2)
            ->where('testData.0.test_date', '2026-01-10')
            ->where('testData.0.rto_minutes', 45)
            ->where('testData.0.rpo_minutes', 30)
            ->where('testData.1.test_date', '2026-01-12')
            ->where('testData.1.rto_minutes', 55)
            ->where('testData.1.rpo_minutes', 25)
        );
});

it('uses kpi targets from database', function () {
    $this->actingAs(User::factory()->create());

    KpiTarget::query()->updateOrCreate(
        ['kpi_type' => 'rto'],
        ['target_minutes' => 90]
    );

    KpiTarget::query()->updateOrCreate(
        ['kpi_type' => 'rpo'],
        ['target_minutes' => 45]
    );

    $this->get('/dashboard')
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('dashboard')
            ->where('targetRto', 90)
            ->where('targetRpo', 45)
        );
});
