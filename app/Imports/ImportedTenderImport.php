<?php

namespace App\Imports;

use App\Models\ImportedTender;
use App\Models\WinningCompany;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportedTenderImport implements ToModel, WithHeadingRow, WithUpserts, WithValidation
{
    use Importable;

    /**
     * @var string
     */
    const FIELD_ID_CONTRATO = 'idcontrato';

    /**
     * @var string
     */
    const FIELD_N_ANUNCIO = 'nanuncio';

    /**
     * @var string
     */
    const FIELD_TIPO_CONTRATO = 'tipocontrato';

    /**
     * @var string
     */
    const FIELD_TIPO_PROCEDIMENTO = 'tipoprocedimento';

    /**
     * @var string
     */
    const FIELD_OBJECTO_CONTRATO = 'objectocontrato';

    /**
     * @var string
     */
    const FIELD_ADJUDICANTES = 'adjudicantes';

    /**
     * @var string
     */
    const FIELD_DATA_PUBLICACAO = 'datapublicacao';

    /**
     * @var string
     */
    const FIELD_DATA_CELEBRACAO_CONTRATO = 'datacelebracaocontrato';

    /**
     * @var string
     */
    const FIELD_PRECO_CONTRATUAL = 'precocontratual';

    /**
     * @var string
     */
    const FIELD_CPV = 'cpv';

    /**
     * @var string
     */
    const FIELD_PRAZO_EXECUCAO = 'prazoexecucao';

    /**
     * @var string
     */
    const FIELD_LOCAL_EXECUCAO = 'localexecucao';

    /**
     * @var string
     */
    const FIELD_FUNDAMENTACAO = 'fundamentacao';

    /**
     * @var string
     */
    const FIELD_WINNING_COMPANY = 'adjudicatarios';

    /**
     * Field to support the WithUpserts concern to avoid duplicates.
     *
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'field_id_contrato';
    }

    /**
     * Get the validation rules that apply to the import.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            self::FIELD_ID_CONTRATO              => ['required'],
            self::FIELD_N_ANUNCIO                => ['present'],
            self::FIELD_TIPO_CONTRATO            => ['present'],
            self::FIELD_TIPO_PROCEDIMENTO        => ['present'],
            self::FIELD_OBJECTO_CONTRATO         => ['present'],
            self::FIELD_ADJUDICANTES             => ['present'],
            self::FIELD_DATA_PUBLICACAO          => ['present'],
            self::FIELD_DATA_CELEBRACAO_CONTRATO => ['present', 'numeric'],
            self::FIELD_PRECO_CONTRATUAL         => ['present', 'numeric'],
            self::FIELD_CPV                      => ['present'],
            self::FIELD_PRAZO_EXECUCAO           => ['present'],
            self::FIELD_LOCAL_EXECUCAO           => ['present'],
            self::FIELD_FUNDAMENTACAO            => ['present'],
            self::FIELD_WINNING_COMPANY          => ['required'],
        ];
    }

    /**
     * Fill the model with data.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): ?Model
    {
        $importedTender = new ImportedTender($this->getImportedTenderPayload($row));

        $winningCompany = $this->getWinningCompany($row);

        $importedTender->winning_company_id = $winningCompany->id;

        return $importedTender;
    }

    /**
     * Build the record payload for the ImportedTender model.
     *
     * @param  array  $row
     * @return array
     */
    protected function getImportedTenderPayload(array $row): array
    {
        return [
            'field_id_contrato'              => $row[self::FIELD_ID_CONTRATO],
            'field_n_anuncio'                => $row[self::FIELD_N_ANUNCIO],
            'field_tipo_contrato'            => $row[self::FIELD_TIPO_CONTRATO],
            'field_tipo_procedimento'        => $row[self::FIELD_TIPO_PROCEDIMENTO],
            'field_objecto_contrato'         => $row[self::FIELD_OBJECTO_CONTRATO],
            'field_adjudicantes'             => $row[self::FIELD_ADJUDICANTES],
            'field_data_publicacao'          => Date::excelToDateTimeObject($row[self::FIELD_DATA_PUBLICACAO]),
            'field_data_celebracao_contrato' => Date::excelToDateTimeObject($row[self::FIELD_DATA_CELEBRACAO_CONTRATO]),
            'field_preco_contratual'         => $row[self::FIELD_PRECO_CONTRATUAL],
            'field_cpv'                      => $row[self::FIELD_CPV],
            'field_prazo_execucao'           => $row[self::FIELD_PRAZO_EXECUCAO],
            'field_local_execucao'           => $row[self::FIELD_LOCAL_EXECUCAO],
            'field_fundamentacao'            => $row[self::FIELD_FUNDAMENTACAO],
        ];
    }

    /**
     * Return WinningCompany model.
     *
     * @param  array  $row
     * @return \App\Models\WinningCompany
     */
    protected function getWinningCompany(array $row): WinningCompany
    {
        $payload = $this->getWinningCompanyPayload($row);

        return WinningCompany::firstOrCreate(['code' => $payload['code']], $payload);
    }

    /**
     * Build the record payload for the WinningCompany model.
     *
     * @param  array  $row
     * @return array
     */
    protected function getWinningCompanyPayload(array $row): array
    {
        [$code, $name] = explode('-', $row[self::FIELD_WINNING_COMPANY]);

        return [
            'code' => trim($code),
            'name' => trim($name),
        ];
    }
}
