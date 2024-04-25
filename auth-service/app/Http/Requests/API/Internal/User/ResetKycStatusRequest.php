<?php

namespace App\Http\Requests\API\Internal\User;

use Illuminate\Foundation\Http\FormRequest;

class ResetKycStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'int',
                'exists:users,id',
            ],
        ];
    }
}
