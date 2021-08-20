<?php

namespace App\Http\Controllers;

use App\Http\Resources\TenderResource;
use App\Http\Resources\TendersResource;
use App\Http\Resources\UploadedFileResource;
use App\Imports\TenderImport;
use App\Jobs\UpdateUploadedFileStatus;
use App\Models\Tender;
use App\Models\UploadedFile;
use App\Models\WinningCompany;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile as UploadedFileHttp;

class TenderController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        // get file
        $file = $request->file('spreadsheet');

        // create UploadedFile model
        $uploadedFile = $this->createUploadedFile($file);

        // import tenders from file (in queues)
        $this->importTenders($file, $uploadedFile);

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
        $tenders = $this->getTenderQuery($filters)->paginate();

        return TendersResource::collection($tenders);
    }

    /**
     * @param  mixed  $tenderId
     * @return \Illuminate\Http\Response
     */
    public function show($tenderId)
    {
        // get the tender
        $tender = Tender::findOrFail($tenderId);

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
        $tender = Tender::findOrFail($tenderId);

        // check if the tender was read
        $wasRead = $tender->wasRead();

        return [
            'meta' => ['was-read' => $wasRead]
        ];
    }

    /**
     * Return an instance of query.
     *
     * @param  mixed  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getTenderQuery($filters = [])
    {
        // get base query
        $query = Tender::query();

        // get query based on the related model (WinningCompany) if needed
        if (isset($filters['winning_company_id'])) {
            $winningCompany = WinningCompany::findOrFail($filters['winning_company_id']);

            $query = $winningCompany->tenders();

            unset($filters['winning_company_id']);
        }

        // apply filters and return the query instance
        return $query->applyFilters($filters)->orderByDesc('created_at');
    }

    /**
     * Create a UploadedFile model.
     *
     * @param  \App\Http\Controllers\UploadedFile  $file
     * @return \App\Models\UploadedFile
     */
    protected function createUploadedFile(UploadedFileHttp $file): UploadedFile
    {
        return UploadedFile::create([
            'name'      => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size'      => $file->getSize(),
            'status'    => config('statuses.queued'),
        ]);
    }

    /**
     * Import tenders.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  \App\Models\UploadedFile  $uploadedFile
     * @return void
     */
    protected function importTenders($file, $uploadedFile): void
    {
        (new TenderImport($uploadedFile))->queue($file)->chain([
            new UpdateUploadedFileStatus($uploadedFile),
        ]);
    }
}
