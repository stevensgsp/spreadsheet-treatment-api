<?php

namespace App\Repositories;

use App\Imports\TenderImport;
use App\Jobs\UpdateUploadedFileStatus;
use App\Models\Tender;
use App\Models\WinningCompany;

class TenderRepository extends BaseRepository
{
    /**
     * {@inheritdoc}
     */
    public function model(): string
    {
        return Tender::class;
    }

    /**
     * Return paginated models filtered by specified filters.
     *
     * @param  mixed  $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function get($filters)
    {
        return $this->getTenderQuery($filters)->paginate();
    }

    /**
     * Import tenders.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  \App\Models\UploadedFile  $uploadedFile
     * @return void
     */
    public function import($file, $uploadedFile): void
    {
        (new TenderImport($uploadedFile))->queue($file)->chain([
            new UpdateUploadedFileStatus($uploadedFile),
        ]);
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
        $query = $this->model->query();

        // get query based on the related model (WinningCompany) if needed
        if (isset($filters['winning_company_id'])) {
            $winningCompany = WinningCompany::findOrFail($filters['winning_company_id']);

            $query = $winningCompany->tenders();

            unset($filters['winning_company_id']);
        }

        // apply filters and return the query instance
        return $query->applyFilters($filters)->orderByDesc('created_at');
    }
}
