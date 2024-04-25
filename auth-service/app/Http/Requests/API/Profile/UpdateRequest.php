<?php

namespace App\Http\Requests\API\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('email')) {
            $this->merge([
                'email' => Str::lower($this->input('email')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'string',
            'last_name' => 'nullable|string',
            'nickname' => 'string',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore($this->user()),
            ],
            'avatar' => 'file|mimes:jpg,png',
            'country' => 'string|size:2|nullable',
            'currency' => 'string|size:3|nullable',
            'language' => 'string|nullable|in:ru,en',
            'timezone' => 'timezone|nullable',
            'date_of_birth' => 'date|nullable',
        ];
    }
}
