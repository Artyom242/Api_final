<?php

namespace App\Http\Resources\Api\File;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\UploadedFile;

class UploadFileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'success' => true,
            'message' => 'Success',
            'name' => $this->name,
            'url' => asset('/files/'. $this->file_id),
            'file_id' => $this->file_id,
        ];
    }
}
