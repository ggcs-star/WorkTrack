<?php

namespace App\Support;

use Illuminate\Support\Str;

class RoleLabel
{
    /**
     * Display label for a role name (e.g. "hr" -> "HR", "team leader" -> "Team Leader").
     * Callers decide their own fallback when there's no role at all.
     */
    public static function for(string $roleName): string
    {
        return $roleName === 'hr' ? 'HR' : Str::headline($roleName);
    }
}
