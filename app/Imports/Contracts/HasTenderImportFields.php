<?php

namespace App\Imports\Contracts;

interface HasTenderImportFields
{
    /**
     * @var string
     */
    const CONTRACT_ID = 'idcontrato';

    /**
     * @var string
     */
    const AD_NUMBER = 'nanuncio';

    /**
     * @var string
     */
    const CONTRACT_TYPE = 'tipocontrato';

    /**
     * @var string
     */
    const TENDER_TYPE = 'tipoprocedimento';

    /**
     * @var string
     */
    const CONTRACT_TARGET = 'objectocontrato';

    /**
     * @var string
     */
    const ADJUDICATORS = 'adjudicantes';

    /**
     * @var string
     */
    const WINNING_COMPANIES = 'adjudicatarios';

    /**
     * @var string
     */
    const PUBLICATION_DATE = 'datapublicacao';

    /**
     * @var string
     */
    const CONTRACT_SIGNING_DATE = 'datacelebracaocontrato';

    /**
     * @var string
     */
    const CONTRACT_PRICE = 'precocontratual';

    /**
     * @var string
     */
    const CPV_FIELD = 'cpv';

    /**
     * @var string
     */
    const EXECUTION_TIME = 'prazoexecucao';

    /**
     * @var string
     */
    const EXECUTION_PLACE = 'localexecucao';

    /**
     * @var string
     */
    const LEGAL_BASES = 'fundamentacao';
}
