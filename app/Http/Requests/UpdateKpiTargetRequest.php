<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKpiTargetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'rto_target' => ['required', 'integer', 'min:1'],
            'rpo_target' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'rto_target.required' => 'The RTO target is required.',
            'rto_target.integer' => 'The RTO target must be a whole number.',
            'rto_target.min' => 'The RTO target must be at least 1 minute.',
            'rpo_target.required' => 'The RPO target is required.',
            'rpo_target.integer' => 'The RPO target must be a whole number.',
            'rpo_target.min' => 'The RPO target must be at least 1 minute.',
        ];
    }
}
