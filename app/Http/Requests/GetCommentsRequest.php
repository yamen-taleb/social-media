<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCommentsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'post_id' => 'nullable|exists:posts,id|integer|required_without:parent_id',
            'parent_id' => 'nullable|exists:comments,id|integer|required_without:post_id',
        ];
    }

    public function messages()
    {
        return [
            'post_id.required_without' => 'Post id is required if parent id is not provided.',
            'parent_id.required_without' => 'Parent id is required if post id is not provided.',
        ];
    }

}
