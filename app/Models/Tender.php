<?php

namespace App\Models;

use App\Models\Concerns\AppliesFilters;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    use AppliesFilters;

    /**
     * {@inheritdoc}
     */
    protected static $unguarded = true;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'contract_price' => 'float',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'publication_date',
        'contract_signing_date',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function adjudicators()
    {
        return $this->belongsToMany(Adjudicator::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function winningCompanies()
    {
        return $this->belongsToMany(WinningCompany::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cpvFields()
    {
        return $this->belongsToMany(CpvField::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function executionPlaces()
    {
        return $this->belongsToMany(Place::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contractTypes()
    {
        return $this->belongsToMany(ContractType::class);
    }

    /**
     * Determines if the model was read.
     *
     * @return bool
     */
    public function wasRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Mark the model as read.
     *
     * @return void
     */
    public function markAsRead(): void
    {
        if (! $this->wasRead()) {
            $this->update(['read_at' => now()]);
        }
    }
}
