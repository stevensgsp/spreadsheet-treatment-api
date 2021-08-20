<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TenderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'tenders',
            'id' => $this->id,
            'attributes' => [
                'contract_id'           => $this->contract_id,
                'ad_number'             => $this->ad_number,
                'contract_type'         => $this->contract_type,
                'tender_type'           => $this->tender_type,
                'contract_target'       => $this->contract_target,
                'publication_date'      => $this->publication_date,
                'contract_signing_date' => $this->contract_signing_date,
                'contract_price'        => $this->contract_price,
                'cpv_field'             => $this->cpv_field,
                'execution_time'        => $this->execution_time,
                'execution_place'       => $this->execution_place,
                'legal_bases'           => $this->legal_bases,
                'adjudicators'          => $this->adjudicators,
                'winningCompanies'      => $this->winningCompanies,
                'cpvFields'             => $this->cpvFields,
                'executionPlaces'       => $this->executionPlaces,
                'contractTypes'         => $this->contractTypes,
            ]
        ];
    }
}
