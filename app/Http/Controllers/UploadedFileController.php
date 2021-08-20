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
     *
     * @OA\Get(
     *     path="/api/uploaded-files",
     *     operationId="index",
     *     tags={"Uploaded Files"},
     *     summary="Index of uploaded files.",
     *
     *     @OA\Response(response=200, description="Success.", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="data", type="array", @OA\Items(
     *             ref="#/components/schemas/UploadedFileResource"
     *         ))
     *     ))
     * )
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
     *
     * @OA\Get(
     *     path="/api/uploaded-files/{uploadedFileId}",
     *     operationId="show",
     *     tags={"Uploaded Files"},
     *     summary="Show an uploaded file.",
     *     @OA\Parameter(name="uploadedFileId", required=true, in="path", @OA\Schema(type="string")),
     *
     *     @OA\Response(response=200, description="Success.", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="data", ref="#/components/schemas/UploadedFileResource")
     *     )),
     *     @OA\Response(response=404, description="Not found.")
     * )
     */
    public function show($uploadedFileId)
    {
        // get the uploaded file
        $uploadedFile = $this->uploadedFileRepository->findOrFail($uploadedFileId);

        return new UploadedFileResource($uploadedFile);
    }
}
