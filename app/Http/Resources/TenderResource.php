<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TenderResource",
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="id", type="string", format="binary"),
 *     @OA\Property(property="attributes",
 *         type="object",
 *         required={
 *             "contract_id",
 *             "ad_number",
 *             "contract_type",
 *             "tender_type",
 *             "contract_target",
 *             "publication_date",
 *             "contract_signing_date",
 *             "contract_price",
 *             "cpv_field",
 *             "execution_time",
 *             "execution_place",
 *             "legal_bases",
 *             "adjudicators",
 *             "winningCompanies",
 *             "cpvFields",
 *             "executionPlaces",
 *             "contractTypes"
 *         },
 *         @OA\Property(property="contract_id", type="string"),
 *         @OA\Property(property="ad_number", type="string"),
 *         @OA\Property(property="contract_type", type="string"),
 *         @OA\Property(property="tender_type", type="string"),
 *         @OA\Property(property="contract_target", type="string"),
 *         @OA\Property(property="publication_date", type="string", format="date-time"),
 *         @OA\Property(property="contract_signing_date", type="string", format="date-time"),
 *         @OA\Property(property="contract_price", type="float"),
 *         @OA\Property(property="cpv_field", type="string"),
 *         @OA\Property(property="execution_time", type="string"),
 *         @OA\Property(property="execution_place", type="string"),
 *         @OA\Property(property="legal_bases", type="string"),
 *         @OA\Property(property="adjudicators", type="array", @OA\Items()),
 *         @OA\Property(property="winningCompanies", type="array", @OA\Items()),
 *         @OA\Property(property="cpvFields", type="array", @OA\Items()),
 *         @OA\Property(property="executionPlaces", type="array", @OA\Items()),
 *         @OA\Property(property="contractTypes", type="array", @OA\Items())
 *     )
 * )
 */

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
