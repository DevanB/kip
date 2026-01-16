<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeveloperFormRequest extends FormRequest
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
        $developerId = $this->route('developer')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('developers', 'email')
                    ->ignore($developerId),
            ],
            'github_username' => ['nullable', 'string', 'max:255'],
            'gitlab_username' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'name.max' => 'Name must not exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email already exists.',
            'github_username.string' => 'GitHub username must be a string.',
            'github_username.max' => 'GitHub username must not exceed 255 characters.',
            'gitlab_username.string' => 'GitLab username must be a string.',
            'gitlab_username.max' => 'GitLab username must not exceed 255 characters.',
        ];
    }
}
