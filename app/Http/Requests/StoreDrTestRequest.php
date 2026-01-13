<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDrTestRequest extends FormRequest
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
        ];
    }
}
