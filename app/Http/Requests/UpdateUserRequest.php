<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? $this->route('id');

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => [
                'sometimes',
                'required',
                'string',
                'min:8',
            ],
        ];
    }
}
