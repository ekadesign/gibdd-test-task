<?php

namespace App\Http\Requests\API\Internal\User;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'name'          => 'string',
            'last_name'     => 'nullable|string',
            'nickname'      => 'string',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users')->ignore($this->route('user')),
            ],
            'avatar'        => 'file|mimes:jpg,png',
            'phone'         => 'nullable|string|unique:users,phone',
            'approved_name' => 'string',
            'status'        => 'nullable|string',
            'status_kyc'    => 'nullable|string',
            'country'       => 'string|size:2|nullable',
            'currency'      => 'string|size:3|nullable',
            'language'      => 'string|nullable|in:ru,en',
            'timezone'      => 'timezone|nullable',
            'date_of_birth' => 'datetime|nullable',
        ];
    }
}
