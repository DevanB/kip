<?php

use App\Models\Developer;
use App\Models\User;

it('returns paginated list of developers', function () {
    $this->actingAs(User::factory()->create());
    Developer::factory()->count(20)->create();

    $response = $this->get('/developers');

    $response->assertSuccessful();

    $data = $response->json();
    expect($data)->toHaveKey('data')
        ->and(count($data['data']))->toBe(15)
        ->and($data['data'][0])->toHaveKeys(['id', 'name', 'email']);
});

it('returns single developer by ID', function () {
    $this->actingAs(User::factory()->create());
    $developer = Developer::factory()->create();

    $response = $this->get("/developers/{$developer->id}");

    $response->assertSuccessful();
    expect($response->json('id'))->toBe($developer->id)
        ->and($response->json('name'))->toBe($developer->name)
        ->and($response->json('email'))->toBe($developer->email);
});

it('creates developer with valid data', function () {
    $this->actingAs(User::factory()->create());
    $response = $this->post('/developers', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'github_username' => 'johndoe',
        'gitlab_username' => 'john_doe',
    ]);

    $response->assertRedirect();

    $developer = Developer::where('email', 'john@example.com')->first();
    expect($developer)->not->toBeNull()
        ->and($developer->name)->toBe('John Doe')
        ->and($developer->github_username)->toBe('johndoe')
        ->and($developer->gitlab_username)->toBe('john_doe');
});

it('requires name when creating developer', function () {
    $this->actingAs(User::factory()->create());
    $response = $this->post('/developers', [
        'email' => 'john@example.com',
    ]);

    $response->assertInvalid(['name']);
});

it('requires email when creating developer', function () {
    $this->actingAs(User::factory()->create());
    $response = $this->post('/developers', [
        'name' => 'John Doe',
    ]);

    $response->assertInvalid(['email']);
});

it('validates email format', function () {
    $this->actingAs(User::factory()->create());
    $response = $this->post('/developers', [
        'name' => 'John Doe',
        'email' => 'not-an-email',
    ]);

    $response->assertInvalid(['email']);
});

it('prevents duplicate email addresses', function () {
    $this->actingAs(User::factory()->create());
    Developer::factory()->create(['email' => 'existing@example.com']);

    $response = $this->post('/developers', [
        'name' => 'Jane Doe',
        'email' => 'existing@example.com',
    ]);

    $response->assertInvalid(['email']);
});

it('allows optional github username', function () {
    $this->actingAs(User::factory()->create());
    $response = $this->post('/developers', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    $response->assertRedirect();

    $developer = Developer::where('email', 'john@example.com')->first();
    expect($developer->github_username)->toBeNull();
});

it('allows optional gitlab username', function () {
    $this->actingAs(User::factory()->create());
    $response = $this->post('/developers', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    $response->assertRedirect();

    $developer = Developer::where('email', 'john@example.com')->first();
    expect($developer->gitlab_username)->toBeNull();
});

it('updates developer with valid data', function () {
    $this->actingAs(User::factory()->create());
    $developer = Developer::factory()->create();

    $response = $this->put("/developers/{$developer->id}", [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'github_username' => 'janedoe',
    ]);

    $response->assertRedirect();

    $developer->refresh();
    expect($developer->name)->toBe('Jane Doe')
        ->and($developer->email)->toBe('jane@example.com')
        ->and($developer->github_username)->toBe('janedoe');
});

it('allows email to remain unchanged when updating', function () {
    $this->actingAs(User::factory()->create());
    $developer = Developer::factory()->create(['email' => 'original@example.com']);

    $response = $this->put("/developers/{$developer->id}", [
        'name' => 'Updated Name',
        'email' => 'original@example.com',
    ]);

    $response->assertRedirect();
    expect(Developer::count())->toBe(1);
});

it('prevents email duplication on update', function () {
    $this->actingAs(User::factory()->create());
    $developer1 = Developer::factory()->create(['email' => 'dev1@example.com']);
    Developer::factory()->create(['email' => 'dev2@example.com']);

    $response = $this->put("/developers/{$developer1->id}", [
        'name' => 'Updated',
        'email' => 'dev2@example.com',
    ]);

    $response->assertInvalid(['email']);
});

it('deletes developer', function () {
    $this->actingAs(User::factory()->create());
    $developer = Developer::factory()->create();

    $response = $this->delete("/developers/{$developer->id}");

    $response->assertRedirect();
    expect(Developer::find($developer->id))->toBeNull();
});

it('validates name max length', function () {
    $this->actingAs(User::factory()->create());
    $response = $this->post('/developers', [
        'name' => str_repeat('a', 256),
        'email' => 'test@example.com',
    ]);

    $response->assertInvalid(['name']);
});
