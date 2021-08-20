<?php

namespace App\Models\Concerns;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

trait AppliesFilters
{
    /**
     * Scope a query to filter results.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApplyFilters(Builder $query, $filters = [])
    {
        $filters = (array) $filters;

        if (empty($filters)) {
            return $query;
        }

        foreach ($filters as $fieldName => $fieldValue) {
            // apply "where" if the attribute is casted as number.
            if ($this->hasCast($fieldName, ['integer', 'float'])) {
                if (strpos($fieldValue, ',')) {
                    $query->whereBetween($fieldName, explode(',', $fieldValue));
                } else {
                    $query->where($fieldName, $fieldValue);
                }
                continue;
            }

            // apply "whereBetween" and convert the attribute into a DateTime instance if needed.
            if ($this->isDateAttribute($fieldName)) {
                if (! is_null($dates = $this->getStartAndEndDates($fieldValue))) {
                    $query->whereBetween($fieldName, $dates);
                }
                continue;
            }

            // apply "where" with the "like" operator.
            $query->where($fieldName, 'like', '%' . $fieldValue . '%');
        }

        return $query;
    }

    /**
     * Gets start and end of given dates, respectively.
     *
     * @param  string  $dates
     * @return array|null
     */
    protected function getStartAndEndDates(string $dates): ?array
    {
        try {
            list($startDate, $endDate) = explode(',', $dates);

            $startOfDay = Carbon::parse($startDate)->startOfDay();
            $endOfDay = Carbon::parse($endDate)->endOfDay();
        } catch (Exception | Throwable $e) {
            Log::error('Error when filtering model by date field', [$dates]);

            return null;
        }

        return [$this->fromDateTime($startOfDay), $this->fromDateTime($endOfDay)];
    }
}
