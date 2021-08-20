<?php

namespace App\Repositories;

use App\Models\UploadedFile;
use Illuminate\Http\UploadedFile as UploadedFileHttp;

class UploadedFileRepository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    public function model(): string
    {
        return UploadedFile::class;
    }

    /**
     * Create a UploadedFile model.
     *
     * @param  \App\Http\Controllers\UploadedFile  $file
     * @return \App\Models\UploadedFile
     */
    public function createByFile(UploadedFileHttp $file): UploadedFile
    {
        return $this->model->create([
            'name'      => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size'      => $file->getSize(),
            'status'    => config('statuses.queued'),
        ]);
    }
}
