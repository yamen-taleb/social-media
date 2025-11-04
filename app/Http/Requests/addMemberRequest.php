<?php

namespace App\Http\Requests;

use App\Enums\UserApprovalEnum;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class addMemberRequest extends FormRequest
{
    public ?GroupUser $groupUser;
    public ?User $user;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('addNewMember', $this->group);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->user = User::query()->where('id', $value)->first();

                    if (!$this->user) {
                        $fail('User not found.');
                        return;
                    }

                    $this->groupUser = GroupUser::where('user_id', $value)
                        ->where('group_id', $this->group->id)
                        ->first();

                    if ($this->groupUser?->status === UserApprovalEnum::APPROVED->value) {
                        $fail('This user is already a member of this group.');
                    }
                }
            ]
        ];
    }
}
