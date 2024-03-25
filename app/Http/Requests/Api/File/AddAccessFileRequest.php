<?php

namespace App\Http\Requests\Api\File;

use App\Http\Requests\Api\apiRequest;
use Illuminate\Foundation\Http\FormRequest;

class AddAccessFileRequest extends apiRequest
{
    public function rules()
    {
        return [
            'email' => ['required']
        ];
    }
}
