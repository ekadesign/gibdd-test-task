<?php

namespace App\Http\Requests\API\Internal\Register;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class AuthKeyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'auth_key' => 'required|string|min:20',
            'name' => 'nullable|string',
            'nickname' => 'nullable|string|min:2',
            'email' => 'nullable|required_without:nickname|email',
            'password' => 'required|string|min:3',
            'provider' => 'nullable|string',
            'provider_id' => 'nullable|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('promocode')) {
            $this->merge([
                'promocode' => Str::upper($this->input('promocode'))
            ]);
        }
    }
}
