<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetUsersRequest extends FormRequest
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
            'search' => [
                'nullable',
                'string',
                'max:100',
                'min:1'
            ],
            'per_page' => [
                'nullable',
                'integer',
                'min:5',
                'max:100'
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1'
            ],
            'sort_by' => [
                'nullable',
                'string',
                'in:name,email,created_at'
            ],
            'sort_order' => [
                'nullable',
                'string',
                'in:asc,desc'
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
            'search.string' => 'The search term must be a valid string.',
            'search.max' => 'The search term may not be greater than 100 characters.',
            'search.min' => 'The search term must be at least 1 character.',

            'per_page.integer' => 'The per page value must be an integer.',
            'per_page.min' => 'The per page value must be at least 5.',
            'per_page.max' => 'The per page value may not be greater than 100.',

            'page.integer' => 'The page value must be an integer.',
            'page.min' => 'The page value must be at least 1.',

            'sort_by.in' => 'The sort by field must be one of: name, email, created_at.',
            'sort_order.in' => 'The sort order must be either asc or desc.',
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
            'search' => 'search term',
            'per_page' => 'items per page',
            'page' => 'page number',
            'sort_by' => 'sort field',
            'sort_order' => 'sort order',
        ];
    }
}
