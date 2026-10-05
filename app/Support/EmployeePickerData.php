<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Collection;

class EmployeePickerData
{
    /**
     * Shape a collection of employees for the Alpine employee-picker sidebar
     * used on the task create/edit forms.
     */
    public static function forJs(Collection $employees): array
    {
        return $employees->map(fn ($e) => [
            'id' => (string) $e->id,
            'name' => $e->name,
            'email' => $e->email,
            'designation' => $e->profile?->designation,
            'department' => $e->profile?->department,
            'mobile' => $e->profile?->mobile_number,
            'photo' => $e->profile?->photo_path ? asset('storage/'.$e->profile->photo_path) : null,
        ])->values()->all();
    }
}
