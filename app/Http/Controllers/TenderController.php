<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportTenderRequest;
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
     * @param  \App\Http\Requests\ImportTenderRequest  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     path="/api/tenders/import",
     *     operationId="import",
     *     tags={"Tenders"},
     *     summary="Import tenders.",
     *     @OA\RequestBody(required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(type="object",
     *                 @OA\Property(property="spreadsheet", type="string", format="binary")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Success.", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="data", ref="#/components/schemas/UploadedFileResource")
     *     )),
     *     @OA\Response(response=422, description="The given data was invalid.")
     * )
     */
    public function import(ImportTenderRequest $request)
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
     *
     * @OA\Get(
     *     path="/api/tenders",
     *     operationId="index",
     *     tags={"Tenders"},
     *     summary="Index of tenders.",
     *     @OA\Parameter(name="filter[contract_signing_date]", in="query", @OA\Schema(type="string"),
     *         example="2016/01/06,2016/01/06"
     *     ),
     *     @OA\Parameter(name="filter[contract_price]", in="query", @OA\Schema(type="string"),
     *         example="3001,7000"
     *     ),
     *     @OA\Parameter(name="filter[winning_company_id]", in="query", @OA\Schema(type="string"),
     *         example="1223"
     *     ),
     *
     *     @OA\Response(response=200, description="Success.", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="data", type="array", @OA\Items(
     *             ref="#/components/schemas/TendersResource"
     *         ))
     *     ))
     * )
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
     *
     * @OA\Get(
     *     path="/api/tenders/{tenderId}",
     *     operationId="show",
     *     tags={"Tenders"},
     *     summary="Show a tender.",
     *     @OA\Parameter(name="tenderId", required=true, in="path", @OA\Schema(type="string")),
     *
     *     @OA\Response(response=200, description="Success.", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="data", ref="#/components/schemas/TenderResource")
     *     )),
     *     @OA\Response(response=404, description="Not found.")
     * )
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
     *
     * @OA\Get(
     *     path="/api/tenders/{tenderId}/was-read",
     *     operationId="wasRead",
     *     tags={"Tenders"},
     *     summary="Check if tender was read.",
     *     @OA\Parameter(name="tenderId", required=true, in="path", @OA\Schema(type="string")),
     *
     *     @OA\Response(response=200, description="Success.", @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="meta", type="object",
     *             @OA\Property(property="was-read", type="boolean")
     *         )
     *     )),
     *     @OA\Response(response=404, description="Not found.")
     * )
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
