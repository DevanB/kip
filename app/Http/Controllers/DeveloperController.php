<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeveloperFormRequest;
use App\Models\Developer;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DeveloperController extends Controller
{
    public function index(): Response
    {
        $developers = Developer::query()
            ->get()
            ->map(fn (Developer $dev) => [
                'id' => $dev->id,
                'name' => $dev->name,
                'email' => $dev->email,
                'github_username' => $dev->github_username,
                'gitlab_username' => $dev->gitlab_username,
            ]);

        return Inertia::render('developers/index', [
            'developers' => $developers,
        ]);
    }

    public function show(Developer $developer): Response
    {
        return Inertia::render('developers/show', [
            'developer' => [
                'id' => $developer->id,
                'name' => $developer->name,
                'email' => $developer->email,
                'github_username' => $developer->github_username,
                'gitlab_username' => $developer->gitlab_username,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('developers/create');
    }

    public function edit(Developer $developer): Response
    {
        return Inertia::render('developers/edit', [
            'developer' => [
                'id' => $developer->id,
                'name' => $developer->name,
                'email' => $developer->email,
                'github_username' => $developer->github_username,
                'gitlab_username' => $developer->gitlab_username,
            ],
        ]);
    }

    public function store(DeveloperFormRequest $request): RedirectResponse
    {
        Developer::create($request->validated());

        return redirect()->back();
    }

    public function update(DeveloperFormRequest $request, Developer $developer): RedirectResponse
    {
        $developer->update($request->validated());

        return redirect()->back();
    }

    public function destroy(Developer $developer): RedirectResponse
    {
        $developer->delete();

        return redirect()->back();
    }
}
