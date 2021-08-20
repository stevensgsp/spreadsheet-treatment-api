<?php

namespace App\Imports;

use App\Imports\Contracts\HasTenderImportFields;
use App\Jobs\UpdateUploadedFileStatus;
use App\Models\Adjudicator;
use App\Models\ContractType;
use App\Models\CpvField;
use App\Models\Place;
use App\Models\Tender;
use App\Models\UploadedFile;
use App\Models\WinningCompany;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TenderImport implements
    OnEachRow,
    WithHeadingRow,
    WithChunkReading,
    WithEvents,
    WithValidation,
    ShouldQueue,
    HasTenderImportFields
{
    use Importable;

    /**
     * The number of chunks to read the spreadsheet.
     *
     * @var int
     */
    protected $chunkSize = 1000;

    /**
     * @var \App\Models\UploadedFile
     */
    protected $uploadedFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }

    /**
     * Fill the model with data.
     *
     * @param  \Maatwebsite\Excel\Row  $row
     * @return void
     */
    public function onRow(Row $row): void
    {
        // get row as an array
        $arrayRow = $row->toArray();

        // find or store the tender
        $tender = $this->getTender($arrayRow);

        // sync tender with winning companies (adjudicatarios)
        $tender->winningCompanies()->sync(
            $this->resolveTenderRelatedModels($row[self::WINNING_COMPANIES], WinningCompany::class)
        );

        // sync tender with adjudicators (adjudicantes)
        $tender->adjudicators()->sync(
            $this->resolveTenderRelatedModels($row[self::ADJUDICATORS], Adjudicator::class)
        );

        // sync tender with cpvFields (cpv)
        $tender->cpvFields()->sync(
            $this->resolveTenderRelatedModels($row[self::CPV_FIELD], CpvField::class)
        );

        // sync tender with contractTypes (tipocontrato)
        $tender->contractTypes()->sync(
            $this->resolveTenderRelatedModels($row[self::CONTRACT_TYPE], ContractType::class)
        );

        // sync tender with executionPlaces (localexecucao)
        $tender->executionPlaces()->sync(
            $this->resolveTenderRelatedModels($row[self::EXECUTION_PLACE], Place::class)
        );
    }

    /**
     * Return Tender model.
     *
     * @param  array  $row
     * @return array
     */
    protected function getTender(array $row): Tender
    {
        $payload = $this->getTenderPayload($row);

        return Tender::firstOrCreate(['contract_id' => $payload['contract_id']], $payload);
    }

    /**
     * Build the record payload for the Tender model.
     *
     * @param  array  $row
     * @return array
     */
    protected function getTenderPayload(array $row): array
    {
        return [
            'contract_id'           => $row[self::CONTRACT_ID],
            'ad_number'             => $row[self::AD_NUMBER],
            'tender_type'           => $row[self::TENDER_TYPE],
            'contract_target'       => $row[self::CONTRACT_TARGET],
            'publication_date'      => Date::excelToDateTimeObject($row[self::PUBLICATION_DATE]),
            'contract_signing_date' => Date::excelToDateTimeObject($row[self::CONTRACT_SIGNING_DATE]),
            'contract_price'        => $row[self::CONTRACT_PRICE],
            'execution_time'        => $row[self::EXECUTION_TIME],
            'legal_bases'           => $row[self::LEGAL_BASES],
            'uploaded_file_id'      => $this->uploadedFile->id,
        ];
    }

    /**
     * Return Tender related models.
     *
     * @param  string  $cellData
     * @param  string  $modelClass
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function resolveTenderRelatedModels(string $cellData, string $modelClass): Collection
    {
        $models = [];

        $payloads = $this->getTenderRelatedPayloads($cellData);

        // iterate over each payload and stored
        foreach ($payloads as $payload) {
            $models[] = $modelClass::firstOrCreate(['code' => $payload['code']], $payload);
        }

        return new Collection($models);
    }

    /**
     * Build the record payloads for the table.
     *
     * @param  string  $cellData
     * @return array
     */
    protected function getTenderRelatedPayloads(string $cellData): array
    {
        $payload = [];

        // get an array with raw data records
        $rawRecords = explode('|', $cellData);

        // iterate over each raw data record
        foreach ($rawRecords as $rawRecord) {
            // get the payload
            try {
                [$code, $name] = explode(' - ', $rawRecord);
            } catch (Exception $e) {
                $code = Str::slug($rawRecord);
                $name = $rawRecord;
            }

            $payload[] = ['code' => trim($code), 'name' => trim($name)];
        }

        return $payload;
    }

    /**
     * Prepare the data for validation.
     *
     * @param  mixed  $data
     * @param  mixed  $index
     * @return mixed
     */
    public function prepareForValidation($data, $index)
    {
        foreach ($data as $key => $value) {
            // parse 'NULL' values to null
            if ($value === 'NULL') {
                $data[$key] = null;
            }
        }

        return $data;
    }

    /**
     * Get the validation rules that apply to the import.
     *
     * @param array  $row
     * @return void
     */
    public function rules(): array
    {
        return [
            self::CONTRACT_ID           => ['required'],
            self::AD_NUMBER             => ['present'],
            self::CONTRACT_TYPE         => ['present'],
            self::TENDER_TYPE           => ['present'],
            self::CONTRACT_TARGET       => ['present'],
            self::ADJUDICATORS          => ['required'],
            self::WINNING_COMPANIES     => ['required'],
            self::PUBLICATION_DATE      => ['present', 'numeric'],
            self::CONTRACT_SIGNING_DATE => ['present', 'numeric'],
            self::CONTRACT_PRICE        => ['present', 'numeric'],
            self::CPV_FIELD             => ['present'],
            self::EXECUTION_TIME        => ['present'],
            self::EXECUTION_PLACE       => ['present'],
            self::LEGAL_BASES           => ['present'],
        ];
    }

    /**
     * The number of chunks to read the spreadsheet.
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return $this->chunkSize;
    }

    /**
     * Register events for import.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                // set processing status
                UpdateUploadedFileStatus::dispatch($this->uploadedFile, config('statuses.processing'));
            },

            ImportFailed::class => function (ImportFailed $event) {
                // set failed status
                UpdateUploadedFileStatus::dispatch($this->uploadedFile, config('statuses.failed'));
            },
        ];
    }
}
