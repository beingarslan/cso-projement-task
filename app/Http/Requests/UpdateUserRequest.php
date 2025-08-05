<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . ($this->user_id ?? 'NULL')
            ],
            'password' => [
                'nullable', // Password is optional on update
                'string',
                'min:6',
                'max:255'
            ],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id'
            ]
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'name.min' => 'The name must be at least 2 characters.',

            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'email.unique' => 'This email address is already taken by another user.',

            'password.string' => 'The password must be a valid string.',
            'password.min' => 'The password must be at least 6 characters.',
            'password.max' => 'The password may not be greater than 255 characters.',

            'user_id.required' => 'User ID is required for updates.',
            'user_id.integer' => 'User ID must be a valid integer.',
            'user_id.exists' => 'The specified user does not exist.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'password' => 'password',
            'user_id' => 'user identifier',
        ];
    }
}
