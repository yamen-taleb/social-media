<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public static $attachmentExtensions = 'jpg,jpeg,png,gif,webp,mp4,webm,mov,avi';
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $description = $this->input('description');
        $cleaned = preg_replace('/<p>(?:\s|&nbsp;|<br\s*\/?>)*<\/p>/i', '', $description);

        return $this->merge([
            'user_id' => auth()->user()->id,
            'description' => $cleaned,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*' => ['required', 'file', 'mimes:' . self::$attachmentExtensions, 'max:10240'],
            'group_id' => ['nullable', 'exists:groups,id'],
            'user_id' => ['required'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $extensions = str_replace(',', ', ', self::$attachmentExtensions);
        
        return [
            'attachments.max' => 'You can upload a maximum of :max files.',
            'attachments.*.required' => 'The attachment file is required.',
            'attachments.*.file' => 'The attachment must be a valid file.',
            'attachments.*.mimes' => 'Invalid file type. Allowed types: ' . $extensions . '.',
            'attachments.*.max' => 'The file size must not exceed 10MB.',
            'description.max' => 'The post description must not exceed 1000 characters.',
            'title.max' => 'The post title must not exceed 255 characters.',
            'group_id.exists' => 'The selected group does not exist.',
        ];
    }
}
