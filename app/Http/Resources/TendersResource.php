<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TendersResource",
 *     @OA\Property(property="type", type="string"),
 *     @OA\Property(property="id", type="string", format="binary"),
 *     @OA\Property(property="attributes",
 *         type="object",
 *         required={
 *             "contract_signing_date",
 *             "contract_price",
 *             "winningCompanies"
 *         },
 *         @OA\Property(property="contract_signing_date", type="string", format="date-time"),
 *         @OA\Property(property="contract_price", type="float"),
 *         @OA\Property(property="winningCompanies", type="array", @OA\Items())
 *     )
 * )
 */

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
