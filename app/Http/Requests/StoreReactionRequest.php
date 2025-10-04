<?php

namespace App\Http\Requests;

use App\ReactionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReactionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => [Rule::enum(ReactionEnum::class), 'required'],
            'model' => ['required', 'string', Rule::in(['Post', 'Comment'])],
        ];
    }
}
