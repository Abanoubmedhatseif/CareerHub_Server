<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone_number' => ['required', 'string', 'regex:/^01[0125][0-9]{8}$/'],
            'profile_image' => 'sometimes|image',
            'cover_image' => 'sometimes|image',
            'role' => [
                'required',
                Rule::in(['employer', 'candidate', 'admin']),
            ]
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'role' => strtolower($this->role)
        ]);
    }
}
