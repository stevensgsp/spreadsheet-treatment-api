<?php

namespace App\Http\Controllers;

use App\Http\Resources\UploadedFileResource;
use App\Repositories\UploadedFileRepository;

class UploadedFileController extends Controller
{
    /**
     * Creates a new class instance.
     *
     * @return void
     */
    public function __construct(UploadedFileRepository $uploadedFileRepo)
    {
        $this->uploadedFileRepository = $uploadedFileRepo;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get uploaded files
        $uploadedFiles = $this->uploadedFileRepository->paginate();

        return UploadedFileResource::collection($uploadedFiles);
    }

    /**
     * @param  mixed  $uploadedFileId
     * @return \Illuminate\Http\Response
     */
    public function show($uploadedFileId)
    {
        // get the uploaded file
        $uploadedFile = $this->uploadedFileRepository->findOrFail($uploadedFileId);

        return new UploadedFileResource($uploadedFile);
    }
}
