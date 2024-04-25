<?php

namespace App\Http\Requests\API\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginWebAppRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'init_data' => 'required|string',
        ];
    }
}
