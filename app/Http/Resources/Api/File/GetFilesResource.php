<?php

namespace App\Http\Resources\Api\File;

use Illuminate\Http\Resources\Json\JsonResource;

class GetFilesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'file_id' => $this->file_id,
            'name' => $this->name,
            'url' => asset('/files/'. $this->file_id),
        ];
    }
}
