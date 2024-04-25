<?php

namespace App\Http\Requests\API\Auth;

use App\Helpers\Formatter;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ConfirmPhoneCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //we may add throttle
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'string',
                'phone:INTERNATIONAL',
            ],
            'code'  => 'required|string',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'phone' => Formatter::phone($this->input('phone')),
        ]);
    }
}
