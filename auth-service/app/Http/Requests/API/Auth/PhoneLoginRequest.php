<?php

namespace App\Http\Requests\API\Auth;

use App\Helpers\Formatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PhoneLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'string|nullable',
            'last_name'  => 'string|nullable',
            'phone'      => 'required_without:email|nullable|phone:INTERNATIONAL',
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('phone')) {
            $this->merge([
                'phone' => Formatter::phone($this->input('phone')),
            ]);
        }
    }
}
