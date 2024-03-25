<?php

namespace App\Http\Resources\Api\File;

use Illuminate\Http\Resources\Json\JsonResource;

class AccessFileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'fullname' => $this->first_name . ' '. $this->last_name,
            'email' => $this->email,
            'type' => $this->pivot->allow_id === 1 ? 'author' : 'co-author',
        ];
    }
}
