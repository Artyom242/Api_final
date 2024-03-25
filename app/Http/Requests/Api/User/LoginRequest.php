<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\apiRequest;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends apiRequest
{
    public function rules()
    {
        return [
            'email' => ['required'],
            'password' => ['required'],
        ];
    }
}
