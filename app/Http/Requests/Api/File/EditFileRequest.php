<?php

namespace App\Http\Requests\Api\File;

use App\Http\Requests\Api\apiRequest;
use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditFileRequest extends apiRequest
{
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique(File::class, 'name')]
        ];
    }
}
