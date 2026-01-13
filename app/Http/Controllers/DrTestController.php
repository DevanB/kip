<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDrTestRequest;
use App\Models\DrTest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DrTestController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('dr-tests/create');
    }

    public function store(StoreDrTestRequest $request): RedirectResponse
    {
        DrTest::create($request->validated());

        return redirect()->route('dashboard');
    }
}
