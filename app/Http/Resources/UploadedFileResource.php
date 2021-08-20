<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UploadedFileResource",
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="id", type="string", format="binary"),
 *     @OA\Property(property="attributes",
 *         type="object",
 *         required={
 *             "name",
 *             "mime_type",
 *             "size",
 *             "status"
 *         },
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="mime_type", type="string"),
 *         @OA\Property(property="size", type="string"),
 *         @OA\Property(property="status", type="string")
 *     )
 * )
 */
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
