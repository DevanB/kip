<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeveloperFormRequest;
use App\Models\Developer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class DeveloperController extends Controller
{
    public function index(): JsonResponse
    {
        $developers = Developer::query()
            ->paginate(15);

        return response()->json($developers);
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
