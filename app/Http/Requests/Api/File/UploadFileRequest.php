<?php

namespace App\Http\Requests\Api\File;

use App\Http\Requests\Api\apiRequest;
use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends apiRequest
{
    public function rules()
    {
        return [
            'files' => ['required']
        ];
    }
}
