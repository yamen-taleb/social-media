<?php

namespace App\Http\Requests;

use App\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->group);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', Rule::enum(RoleEnum::class)],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::exists('group_users', 'user_id')->where('group_id', $this->group->id)
            ],
        ];
    }

    public function validationData()
    {
        return array_merge($this->all(), [
            'user_id' => $this->route('user')->id
        ]);
    }
}
