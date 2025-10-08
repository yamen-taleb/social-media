<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentRequest extends FormRequest
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
            'post_id' => [
                'nullable',
                'integer',
                'exists:posts,id'
            ],
            'body' => [
                'required',
                'string',
                'min:1',
                'max:10000',
            ],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('comments', 'id')->whereNull('parent_id')
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.exists' => 'You can only reply to top-level comments, not to replies.',
        ];
    }
}
