<?php

namespace App\Http\Requests\API\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginAuthKeyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !auth()->check();
    }

    public function rules(): array
    {
        return [
            'auth_key' => 'required|min:20',
            'reference_hash' => 'string|nullable',
            'click_id' => 'string|nullable',
        ];
    }
}
