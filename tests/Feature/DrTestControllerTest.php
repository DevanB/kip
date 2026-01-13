<?php

use App\Models\DrTest;
use App\Models\User;
use Inertia\Testing\AssertableInertia;

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

it('requires authentication', function () {
    $this->get('/dr-tests/create')
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
