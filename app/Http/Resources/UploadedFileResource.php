<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UploadedFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'uploaded_files',
            'id' => $this->id,
            'attributes' => [
                'name'      => $this->name,
                'mime_type' => $this->mime_type,
                'size'      => $this->size,
                'status'    => $this->status,
            ]
        ];
    }
}
