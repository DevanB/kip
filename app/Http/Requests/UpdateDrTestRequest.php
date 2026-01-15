<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDrTestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'test_date' => ['required', 'date'],
            'rto_minutes' => ['required', 'integer', 'min:1'],
            'rpo_minutes' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
            'phases' => ['required', 'array', 'min:1'],
            'phases.*.title' => ['required', 'string', 'max:255'],
            'phases.*.started_at' => ['required', 'date'],
            'phases.*.finished_at' => ['required', 'date', 'after_or_equal:phases.*.started_at'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phases.required' => 'At least one phase is required.',
            'phases.min' => 'At least one phase is required.',
            'phases.*.title.required' => 'Phase title is required.',
            'phases.*.started_at.required' => 'Phase start time is required.',
            'phases.*.finished_at.required' => 'Phase end time is required.',
            'phases.*.finished_at.after_or_equal' => 'End time must be after or equal to start time.',
        ];
    }
}
