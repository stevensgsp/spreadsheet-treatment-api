<?php

namespace App\Http\Controllers;

use App\Http\Resources\TenderResource;
use App\Http\Resources\TendersResource;
use App\Http\Resources\UploadedFileResource;
use App\Repositories\TenderRepository;
use App\Repositories\UploadedFileRepository;
use Illuminate\Http\Request;

class TenderController extends Controller
{
    /**
     * Creates a new class instance.
     *
     * @return void
     */
    public function __construct(
        TenderRepository $tenderRepo,
        UploadedFileRepository $uploadedFileRepo
    ) {
        $this->tenderRepository = $tenderRepo;
        $this->uploadedFileRepository = $uploadedFileRepo;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        // get file
        $file = $request->file('spreadsheet');

        // create UploadedFile model
        $uploadedFile = $this->uploadedFileRepository->createByFile($file);

        // import tenders from file (in queues)
        $this->tenderRepository->import($file, $uploadedFile);

        return new UploadedFileResource($uploadedFile);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // request inputs
        $filters = $request->get('filter');

        // get tenders
        $tenders = $this->tenderRepository->get($filters);

        return TendersResource::collection($tenders);
    }

    /**
     * @param  mixed  $tenderId
     * @return \Illuminate\Http\Response
     */
    public function show($tenderId)
    {
        // get the tender
        $tender = $this->tenderRepository->findOrFail($tenderId);

        // mark the tender as read if needed
        $tender->markAsRead();

        return new TenderResource($tender);
    }

    /**
     * @param  mixed  $tenderId
     * @return \Illuminate\Http\Response
     */
    public function wasRead($tenderId)
    {
        // get the tender
        $tender = $this->tenderRepository->findOrFail($tenderId);

        // check if the tender was read
        $wasRead = $tender->wasRead();

        return [
            'meta' => ['was-read' => $wasRead]
        ];
    }
}
