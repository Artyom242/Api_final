<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\apiRequest;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegRequest extends apiRequest
{
    public function rules()
    {
        return [
            'email' => ['required', Rule::unique(User::class, 'email')],
            'password' => ['required', 'min:3', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'first_name' => ['required'],
            'last_name' => ['required'],
        ];
    }
}
