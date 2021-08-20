<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TendersResource extends JsonResource
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
                'contract_signing_date' => $this->contract_signing_date,
                'contract_price'        => $this->contract_price,
                'winningCompanies'      => $this->winningCompanies,
            ]
        ];
    }
}
