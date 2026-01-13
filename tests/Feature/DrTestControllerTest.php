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

it('creates a new dr test', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 45,
        'rpo_minutes' => 30,
        'notes' => 'Test notes',
    ])
        ->assertRedirect('/dashboard');

    $this->assertDatabaseHas('dr_tests', [
        'rto_minutes' => 45,
        'rpo_minutes' => 30,
        'notes' => 'Test notes',
    ]);

    $drTest = DrTest::first();
    expect($drTest->test_date->format('Y-m-d'))->toBe('2026-01-15');
});

it('creates a dr test without optional notes', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 60,
        'rpo_minutes' => 60,
    ])
        ->assertRedirect('/dashboard');

    $this->assertDatabaseHas('dr_tests', [
        'rto_minutes' => 60,
        'rpo_minutes' => 60,
    ]);

    $drTest = DrTest::first();
    expect($drTest->test_date->format('Y-m-d'))->toBe('2026-01-15');
});

it('validates required fields', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [])
        ->assertSessionHasErrors(['test_date', 'rto_minutes', 'rpo_minutes']);
});

it('validates rto_minutes is a positive integer', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 0,
        'rpo_minutes' => 30,
    ])
        ->assertSessionHasErrors(['rto_minutes']);

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => -5,
        'rpo_minutes' => 30,
    ])
        ->assertSessionHasErrors(['rto_minutes']);
});

it('validates rpo_minutes is a positive integer', function () {
    $this->actingAs(User::factory()->create());

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 45,
        'rpo_minutes' => 0,
    ])
        ->assertSessionHasErrors(['rpo_minutes']);

    $this->post('/dr-tests', [
        'test_date' => '2026-01-15',
        'rto_minutes' => 45,
        'rpo_minutes' => -10,
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
    ])
        ->assertRedirect();
});
