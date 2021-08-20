<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjudicator extends Model
{
    /**
     * {@inheritdoc}
     */
    protected static $unguarded = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tenders()
    {
        return $this->belongsToMany(Tender::class);
    }
}
