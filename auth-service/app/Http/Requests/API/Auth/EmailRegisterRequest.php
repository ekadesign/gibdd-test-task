<?php

namespace App\Http\Requests\API\Auth;

use App\Helpers\Formatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class EmailRegisterRequest extends FormRequest
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
            'first_name' => 'string|required|max:255',
            'last_name'  => 'string|nullable',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:3'
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('email')) {
            $this->merge([
                'email' => Str::lower($this->input('email')),
            ]);
        }
    }
}
