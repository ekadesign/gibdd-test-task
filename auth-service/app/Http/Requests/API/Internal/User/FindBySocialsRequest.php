<?php

namespace App\Http\Requests\API\Internal\User;

use Illuminate\Foundation\Http\FormRequest;

class FindBySocialsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'provider_name' => 'required|string',
            'provider_ids' => 'required|array',
            'provider_ids.*' => 'int',
        ];
    }
}
