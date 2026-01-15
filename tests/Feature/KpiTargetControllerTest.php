<?php

use App\Models\KpiTarget;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

describe('edit', function () {
    it('renders the kpi targets settings page', function () {
        $response = $this->actingAs($this->user)
            ->get('/settings/kpi-targets');

        $response->assertSuccessful();
        $response->assertInertia(fn ($page) => $page
            ->component('settings/kpi-targets')
            ->has('rtoTarget')
            ->has('rpoTarget')
        );
    });

    it('displays current target values', function () {
        KpiTarget::updateOrCreate(['kpi_type' => 'rto'], ['target_minutes' => 45]);
        KpiTarget::updateOrCreate(['kpi_type' => 'rpo'], ['target_minutes' => 30]);

        $response = $this->actingAs($this->user)
            ->get('/settings/kpi-targets');

        $response->assertInertia(fn ($page) => $page
            ->where('rtoTarget', 45)
            ->where('rpoTarget', 30)
        );
    });

    it('displays default values when no targets exist', function () {
        KpiTarget::query()->delete();

        $response = $this->actingAs($this->user)
            ->get('/settings/kpi-targets');

        $response->assertInertia(fn ($page) => $page
            ->where('rtoTarget', 60)
            ->where('rpoTarget', 60)
        );
    });

    it('requires authentication', function () {
        $response = $this->get('/settings/kpi-targets');

        $response->assertRedirect();
    });
});

describe('update', function () {
    it('updates kpi target values', function () {
        $response = $this->actingAs($this->user)
            ->patch('/settings/kpi-targets', [
                'rto_target' => 90,
                'rpo_target' => 45,
            ]);

        $response->assertRedirect('/settings/kpi-targets');

        expect(KpiTarget::where('kpi_type', 'rto')->value('target_minutes'))->toBe(90);
        expect(KpiTarget::where('kpi_type', 'rpo')->value('target_minutes'))->toBe(45);
    });

    it('creates targets if they do not exist', function () {
        KpiTarget::query()->delete();

        $this->actingAs($this->user)
            ->patch('/settings/kpi-targets', [
                'rto_target' => 120,
                'rpo_target' => 60,
            ]);

        expect(KpiTarget::where('kpi_type', 'rto')->exists())->toBeTrue();
        expect(KpiTarget::where('kpi_type', 'rpo')->exists())->toBeTrue();
    });

    it('validates rto_target is required', function () {
        $response = $this->actingAs($this->user)
            ->patch('/settings/kpi-targets', [
                'rpo_target' => 60,
            ]);

        $response->assertSessionHasErrors('rto_target');
    });

    it('validates rpo_target is required', function () {
        $response = $this->actingAs($this->user)
            ->patch('/settings/kpi-targets', [
                'rto_target' => 60,
            ]);

        $response->assertSessionHasErrors('rpo_target');
    });

    it('validates targets must be positive integers', function () {
        $response = $this->actingAs($this->user)
            ->patch('/settings/kpi-targets', [
                'rto_target' => 0,
                'rpo_target' => -5,
            ]);

        $response->assertSessionHasErrors(['rto_target', 'rpo_target']);
    });

    it('requires authentication', function () {
        $response = $this->patch('/settings/kpi-targets', [
            'rto_target' => 60,
            'rpo_target' => 60,
        ]);

        $response->assertRedirect();
    });
});
