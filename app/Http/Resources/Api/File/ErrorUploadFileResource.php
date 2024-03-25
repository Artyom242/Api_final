<?php

namespace App\Http\Resources\Api\File;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\UploadedFile;

/**
 * @mixin UploadedFile
 */
class ErrorUploadFileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'success' => false,
            'message' => 'File not loaded',
            'name' => $this->getClientOriginalName(),
        ];
    }
}
