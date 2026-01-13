<?php

use App\Models\DrTest;
use App\Models\DrTestPhase;

it('creates a dr test with factory', function () {
    $drTest = DrTest::factory()->create();

    expect($drTest->id)->not->toBeNull()
        ->and($drTest->test_date)->not->toBeNull()
        ->and($drTest->rto_minutes)->toBeInt()
        ->and($drTest->rto_minutes)->toBeGreaterThan(0)
        ->and($drTest->rpo_minutes)->toBeInt()
        ->and($drTest->rpo_minutes)->toBeGreaterThan(0);
});

it('creates a dr test phase with factory and calculates duration correctly', function () {
    $phase = DrTestPhase::factory()->create();

    expect($phase->id)->not->toBeNull()
        ->and($phase->dr_test_id)->not->toBeNull()
        ->and($phase->title)->not->toBeEmpty()
        ->and($phase->started_at)->not->toBeNull()
        ->and($phase->finished_at)->not->toBeNull()
        ->and($phase->duration_minutes)->toBeInt()
        ->and($phase->duration_minutes)->toBeGreaterThan(0);

    $expectedDuration = (int) $phase->started_at->diffInMinutes($phase->finished_at);
    expect($phase->duration_minutes)->toBe($expectedDuration);
});

it('creates dr test phase linked to parent dr test', function () {
    $drTest = DrTest::factory()->create();
    $phase = DrTestPhase::factory()->for($drTest)->create();

    expect($phase->dr_test_id)->toBe($drTest->id)
        ->and($phase->drTest->id)->toBe($drTest->id);
});

it('creates dr test with multiple phases', function () {
    $drTest = DrTest::factory()
        ->has(DrTestPhase::factory()->count(3), 'phases')
        ->create();

    expect($drTest->phases)->toHaveCount(3);
    expect($drTest->phases->first())->toBeInstanceOf(DrTestPhase::class);
});
