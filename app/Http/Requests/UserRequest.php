<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'age' => ['required', 'numeric', 'min:0', 'max:200'],
            'height' => ['required', 'numeric'],
            'weight' => ['required', 'numeric'],
            'gender' => ['required', 'string', 'in:f,m'],
            'diseases' => ['nullable', 'array'],
            'allergies' => ['nullable', 'array'],
        ];
    }
}
