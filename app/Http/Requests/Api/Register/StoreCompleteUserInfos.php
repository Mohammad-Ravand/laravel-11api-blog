<?php

namespace App\Http\Requests\Api\Register;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreCompleteUserInfos extends FormRequest
{
    use ApiResponse;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'exists:users,email'],
            'password' => [
                'required',
                'string',
                'confirmed', // Ensures it matches 'password_confirmation'
                Password::min(8) // Minimum length of 8 characters
                ->letters()   // At least one letter
                ->mixedCase() // At least one uppercase and one lowercase
                ->numbers()   // At least one number
                ->symbols()   // At least one special character
                ->uncompromised(), // Not a commonly used or compromised password
            ],
            'password_confirmation' => [
                'required',
                'string',
            ],
        ];
    }
}
