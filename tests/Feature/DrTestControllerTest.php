<?php

use App\Models\DrTest;
use App\Models\DrTestPhase;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

it('renders the dr test history list', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/dr-tests')
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('dr-tests/index')
            ->has('drTests')
        );
});

it('displays dr tests sorted by date newest first', function () {
    $this->actingAs(User::factory()->create());

    $olderTest = DrTest::factory()
        ->has(DrTestPhase::factory()->count(2), 'phases')
        ->create(['test_date' => '2026-01-10', 'rto_minutes' => 30, 'rpo_minutes' => 20]);

    $newerTest = DrTest::factory()
        ->has(DrTestPhase::factory()->count(3), 'phases')
        ->create(['test_date' => '2026-01-15', 'rto_minutes' => 45, 'rpo_minutes' => 35]);

    $this->get('/dr-tests')
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('dr-tests/index')
            ->has('drTests', 2)
            ->where('drTests.0.id', $newerTest->id)
            ->where('drTests.0.test_date', '2026-01-15')
            ->where('drTests.0.rto_minutes', 45)
            ->where('drTests.0.rpo_minutes', 35)
            ->where('drTests.0.phases_count', 3)
            ->where('drTests.1.id', $olderTest->id)
            ->where('drTests.1.test_date', '2026-01-10')
            ->where('drTests.1.phases_count', 2)
        );
});

it('displays empty state when no dr tests exist', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/dr-tests')
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('dr-tests/index')
            ->has('drTests', 0)
        );
});

it('renders the create dr test form', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/dr-tests/create')
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('dr-tests/create')
        );
});

it('creates a new dr test with phases', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 45,
        'rpo_minutes' => 30,
        'notes' => 'Test notes',
        'phases' => [
            [
                'title' => 'Failover initiation',
                'started_at' => '2026-01-15T10:00',
                'finished_at' => '2026-01-15T10:15',
            ],
            [
                'title' => 'System verification',
                'started_at' => '2026-01-15T10:15',
                'finished_at' => '2026-01-15T10:45',
            ],
        ],
    ])
        ->assertRedirect('/dashboard');

    $this->assertDatabaseHas('dr_tests', [
        'rto_minutes' => 45,
        'rpo_minutes' => 30,
        'notes' => 'Test notes',
    ]);

    $drTest = DrTest::first();
    expect($drTest->test_date->format('Y-m-d'))->toBe('2026-01-15');
    expect($drTest->phases)->toHaveCount(2);

    $firstPhase = $drTest->phases->first();
    expect($firstPhase->title)->toBe('Failover initiation');
    expect($firstPhase->duration_minutes)->toBe(15);

    $secondPhase = $drTest->phases->last();
    expect($secondPhase->title)->toBe('System verification');
    expect($secondPhase->duration_minutes)->toBe(30);
});

it('creates a dr test without optional notes', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 60,
        'rpo_minutes' => 60,
        'phases' => [
            [
                'title' => 'Test phase',
                'started_at' => '2026-01-15T09:00',
                'finished_at' => '2026-01-15T10:00',
            ],
        ],
    ])
        ->assertRedirect('/dashboard');

    $this->assertDatabaseHas('dr_tests', [
        'rto_minutes' => 60,
        'rpo_minutes' => 60,
    ]);

    $drTest = DrTest::first();
    expect($drTest->test_date->format('Y-m-d'))->toBe('2026-01-15');
    expect($drTest->phases)->toHaveCount(1);
});

it('validates required fields', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [])
        ->assertSessionHasErrors(['test_date', 'rto_minutes', 'rpo_minutes', 'phases']);
});

it('validates at least one phase is required', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 45,
        'rpo_minutes' => 30,
        'phases' => [],
    ])
        ->assertSessionHasErrors(['phases']);
});

it('validates phase fields are required', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 45,
        'rpo_minutes' => 30,
        'phases' => [
            [
                'title' => '',
                'started_at' => '',
                'finished_at' => '',
            ],
        ],
    ])
        ->assertSessionHasErrors(['phases.0.title', 'phases.0.started_at', 'phases.0.finished_at']);
});

it('validates phase end time is after start time', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 45,
        'rpo_minutes' => 30,
        'phases' => [
            [
                'title' => 'Test phase',
                'started_at' => '2026-01-15T11:00',
                'finished_at' => '2026-01-15T10:00',
            ],
        ],
    ])
        ->assertSessionHasErrors(['phases.0.finished_at']);
});

it('validates rto_minutes is a positive integer', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 0,
        'rpo_minutes' => 30,
        'phases' => [
            [
                'title' => 'Test phase',
                'started_at' => '2026-01-15T10:00',
                'finished_at' => '2026-01-15T10:30',
            ],
        ],
    ])
        ->assertSessionHasErrors(['rto_minutes']);

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => -5,
        'rpo_minutes' => 30,
        'phases' => [
            [
                'title' => 'Test phase',
                'started_at' => '2026-01-15T10:00',
                'finished_at' => '2026-01-15T10:30',
            ],
        ],
    ])
        ->assertSessionHasErrors(['rto_minutes']);
});

it('validates rpo_minutes is a positive integer', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 45,
        'rpo_minutes' => 0,
        'phases' => [
            [
                'title' => 'Test phase',
                'started_at' => '2026-01-15T10:00',
                'finished_at' => '2026-01-15T10:30',
            ],
        ],
    ])
        ->assertSessionHasErrors(['rpo_minutes']);

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 45,
        'rpo_minutes' => -10,
        'phases' => [
            [
                'title' => 'Test phase',
                'started_at' => '2026-01-15T10:00',
                'finished_at' => '2026-01-15T10:30',
            ],
        ],
    ])
        ->assertSessionHasErrors(['rpo_minutes']);
});

it('renders the dr test detail view', function () {
    $this->actingAs(User::factory()->create());

    $drTest = DrTest::factory()
        ->has(DrTestPhase::factory()->count(2), 'phases')
        ->create([
            'test_date' => '2026-01-15',
            'rto_minutes' => 45,
            'rpo_minutes' => 30,
            'notes' => 'Test notes for detail view',
        ]);

    $this->get("/dr-tests/{$drTest->id}")
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('dr-tests/show')
            ->has('drTest')
            ->where('drTest.id', $drTest->id)
            ->where('drTest.test_date', '2026-01-15')
            ->where('drTest.rto_minutes', 45)
            ->where('drTest.rpo_minutes', 30)
            ->where('drTest.notes', 'Test notes for detail view')
            ->has('drTest.phases', 2)
        );
});

it('displays dr test phases with correct data', function () {
    $this->actingAs(User::factory()->create());

    $drTest = DrTest::factory()->create(['test_date' => '2026-01-15']);

    $phase = DrTestPhase::factory()->create([
        'dr_test_id' => $drTest->id,
        'title' => 'Failover initiation',
        'started_at' => '2026-01-15 10:00:00',
        'finished_at' => '2026-01-15 10:30:00',
        'duration_minutes' => 30,
    ]);

    $this->get("/dr-tests/{$drTest->id}")
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('dr-tests/show')
            ->where('drTest.phases.0.id', $phase->id)
            ->where('drTest.phases.0.title', 'Failover initiation')
            ->where('drTest.phases.0.started_at', '2026-01-15 10:00')
            ->where('drTest.phases.0.finished_at', '2026-01-15 10:30')
            ->where('drTest.phases.0.duration_minutes', 30)
        );
});

it('returns 404 for non-existent dr test', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/dr-tests/99999')
        ->assertNotFound();
});

it('requires authentication', function () {
    $this->get('/dr-tests')
        ->assertRedirect();

    $this->get('/dr-tests/create')
        ->assertRedirect();

    $drTest = DrTest::factory()->create();
    $this->get("/dr-tests/{$drTest->id}")
        ->assertRedirect();

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 45,
        'rpo_minutes' => 30,
        'phases' => [
            [
                'title' => 'Test phase',
                'started_at' => '2026-01-15T10:00',
                'finished_at' => '2026-01-15T10:30',
            ],
        ],
    ])
        ->assertRedirect();
});

it('renders the edit dr test form with pre-populated data', function () {
    $this->actingAs(User::factory()->create());

    $drTest = DrTest::factory()
        ->has(DrTestPhase::factory()->count(2), 'phases')
        ->create([
            'test_date' => '2026-01-15',
            'rto_minutes' => 45,
            'rpo_minutes' => 30,
            'notes' => 'Original notes',
        ]);

    $this->get("/dr-tests/{$drTest->id}/edit")
        ->assertSuccessful()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('dr-tests/edit')
            ->has('drTest')
            ->where('drTest.id', $drTest->id)
            ->where('drTest.test_date', '2026-01-15')
            ->where('drTest.rto_minutes', 45)
            ->where('drTest.rpo_minutes', 30)
            ->where('drTest.notes', 'Original notes')
            ->has('drTest.phases', 2)
        );
});

it('updates an existing dr test', function () {
    $this->actingAs(User::factory()->create());

    $drTest = DrTest::factory()
        ->has(DrTestPhase::factory()->count(1), 'phases')
        ->create([
            'test_date' => '2026-01-15',
            'rto_minutes' => 45,
            'rpo_minutes' => 30,
            'notes' => 'Original notes',
        ]);

    $this->put("/dr-tests/{$drTest->id}", [
        'test_date' => '2026-01-20',
        'rto_minutes' => 60,
        'rpo_minutes' => 40,
        'notes' => 'Updated notes',
        'phases' => [
            [
                'title' => 'Updated phase',
                'started_at' => '2026-01-20T14:00',
                'finished_at' => '2026-01-20T15:00',
            ],
        ],
    ])
        ->assertRedirect("/dr-tests/{$drTest->id}");

    $drTest->refresh();
    expect($drTest->test_date->format('Y-m-d'))->toBe('2026-01-20');
    expect($drTest->rto_minutes)->toBe(60);
    expect($drTest->rpo_minutes)->toBe(40);
    expect($drTest->notes)->toBe('Updated notes');
    expect($drTest->phases)->toHaveCount(1);
    expect($drTest->phases->first()->title)->toBe('Updated phase');
    expect($drTest->phases->first()->duration_minutes)->toBe(60);
});

it('replaces all phases when updating', function () {
    $this->actingAs(User::factory()->create());

    $drTest = DrTest::factory()
        ->has(DrTestPhase::factory()->count(3), 'phases')
        ->create();

    $originalPhaseIds = $drTest->phases->pluck('id')->toArray();

    $this->put("/dr-tests/{$drTest->id}", [
        'test_date' => $drTest->test_date->format('Y-m-d'),
        'rto_minutes' => $drTest->rto_minutes,
        'rpo_minutes' => $drTest->rpo_minutes,
        'phases' => [
            [
                'title' => 'New single phase',
                'started_at' => '2026-01-15T10:00',
                'finished_at' => '2026-01-15T10:30',
            ],
        ],
    ])
        ->assertRedirect();

    $drTest->refresh();
    expect($drTest->phases)->toHaveCount(1);
    expect($drTest->phases->first()->title)->toBe('New single phase');

    foreach ($originalPhaseIds as $phaseId) {
        $this->assertDatabaseMissing('dr_test_phases', ['id' => $phaseId]);
    }
});

it('validates required fields when updating', function () {
    $this->actingAs(User::factory()->create());

    $drTest = DrTest::factory()->create();

    $this->put("/dr-tests/{$drTest->id}", [])
        ->assertSessionHasErrors(['test_date', 'rto_minutes', 'rpo_minutes', 'phases']);
});

it('returns 404 when editing non-existent dr test', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/dr-tests/99999/edit')
        ->assertNotFound();

    $this->put('/dr-tests/99999', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 45,
        'rpo_minutes' => 30,
        'phases' => [
            [
                'title' => 'Test phase',
                'started_at' => '2026-01-15T10:00',
                'finished_at' => '2026-01-15T10:30',
            ],
        ],
    ])
        ->assertNotFound();
});

it('deletes a dr test and redirects to history', function () {
    $this->actingAs(User::factory()->create());

    $drTest = DrTest::factory()
        ->has(DrTestPhase::factory()->count(2), 'phases')
        ->create();

    $phaseIds = $drTest->phases->pluck('id')->toArray();

    $this->delete("/dr-tests/{$drTest->id}")
        ->assertRedirect('/dr-tests');

    $this->assertDatabaseMissing('dr_tests', ['id' => $drTest->id]);

    foreach ($phaseIds as $phaseId) {
        $this->assertDatabaseMissing('dr_test_phases', ['id' => $phaseId]);
    }
});

it('returns 404 when deleting non-existent dr test', function () {
    $this->actingAs(User::factory()->create());

    $this->delete('/dr-tests/99999')
        ->assertNotFound();
});

it('requires authentication to delete a dr test', function () {
    $drTest = DrTest::factory()->create();

    $this->delete("/dr-tests/{$drTest->id}")
        ->assertRedirect();

    $this->assertDatabaseHas('dr_tests', ['id' => $drTest->id]);
});
