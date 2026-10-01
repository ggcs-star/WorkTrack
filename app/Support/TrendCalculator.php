<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

class TrendCalculator
{
    /**
     * Compare the count of rows matching $query in the last 7 days against the
     * prior 7-day window, based on $dateColumn. Returns null when there's no
     * prior-period data to compare against (avoids a meaningless/undefined trend).
     */
    public static function weekOverWeek(Builder $query, string $dateColumn = 'created_at'): ?array
    {
        $thisWeek = (clone $query)->where($dateColumn, '>=', now()->subDays(7))->count();
        $lastWeek = (clone $query)->whereBetween($dateColumn, [now()->subDays(14), now()->subDays(7)])->count();

        if ($lastWeek === 0) {
            return null;
        }

        $percent = (int) round((($thisWeek - $lastWeek) / $lastWeek) * 100);

        return [
            'direction' => $percent >= 0 ? 'up' : 'down',
            'percent' => abs($percent),
        ];
    }
}
