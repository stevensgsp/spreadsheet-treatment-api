<?php

namespace App\Http\Controllers;

use App\Http\Resources\UploadedFileResource;
use App\Models\UploadedFile;
use Illuminate\Http\Request;

class UploadedFileController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get uploaded files
        $uploadedFiles = UploadedFile::paginate();

        return UploadedFileResource::collection($uploadedFiles);
    }

    /**
     * @param  mixed  $uploadedFileId
     * @return \Illuminate\Http\Response
     */
    public function show($uploadedFileId)
    {
        // get the uploaded file
        $uploadedFile = UploadedFile::findOrFail($uploadedFileId);

        return new UploadedFileResource($uploadedFile);
    }
}
