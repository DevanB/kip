<?php

use App\Models\Developer;

it('requires name field', function () {
    expect(function () {
        Developer::factory()->create(['name' => null]);
    })->toThrow(Exception::class);
});

it('requires email field', function () {
    expect(function () {
        Developer::factory()->create(['email' => null]);
    })->toThrow(Exception::class);
});

it('enforces email uniqueness', function () {
    $developer1 = Developer::factory()->create(['email' => 'test@example.com']);
    
    expect(function () {
        Developer::factory()->create(['email' => 'test@example.com']);
    })->toThrow(Exception::class);
});

it('allows optional github username', function () {
    $developer = Developer::factory()->create(['github_username' => null]);
    
    expect($developer->github_username)->toBeNull();
});

it('allows optional gitlab username', function () {
    $developer = Developer::factory()->create(['gitlab_username' => null]);
    
    expect($developer->gitlab_username)->toBeNull();
});

it('allows duplicate github usernames', function () {
    Developer::factory()->create(['github_username' => 'testuser']);
    $developer2 = Developer::factory()->create(['github_username' => 'testuser']);
    
    expect($developer2->github_username)->toBe('testuser');
});

it('allows duplicate gitlab usernames', function () {
    Developer::factory()->create(['gitlab_username' => 'testuser']);
    $developer2 = Developer::factory()->create(['gitlab_username' => 'testuser']);
    
    expect($developer2->gitlab_username)->toBe('testuser');
});

it('preserves casing for name', function () {
    $developer = Developer::factory()->create(['name' => 'John Doe']);
    
    expect($developer->name)->toBe('John Doe');
});

it('preserves casing for email', function () {
    $developer = Developer::factory()->create(['email' => 'John.Doe@Example.COM']);
    
    expect($developer->email)->toBe('John.Doe@Example.COM');
});

it('preserves casing for github username', function () {
    $developer = Developer::factory()->create(['github_username' => 'JohnDoe']);
    
    expect($developer->github_username)->toBe('JohnDoe');
});

it('preserves casing for gitlab username', function () {
    $developer = Developer::factory()->create(['gitlab_username' => 'JohnDoe']);
    
    expect($developer->gitlab_username)->toBe('JohnDoe');
});
