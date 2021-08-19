<?php

namespace App\Models;

use App\Models\ImportedTender;
use Illuminate\Database\Eloquent\Model;

class WinningCompany extends Model
{
    /**
     * {@inheritdoc}
     */
    protected static $unguarded = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function importedTender()
    {
        return $this->hasMany(ImportedTender::class);
    }
}
