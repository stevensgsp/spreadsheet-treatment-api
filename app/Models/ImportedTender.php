<?php

namespace App\Models;

use App\Models\WinningCompany;
use Illuminate\Database\Eloquent\Model;

class ImportedTender extends Model
{
    /**
     * {@inheritdoc}
     */
    protected static $unguarded = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function winningCompany()
    {
        return $this->belongsTo(WinningCompany::class);
    }
}
