<?php

namespace App\Http\Controllers\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

trait ManagesAssignableUsers
{
    private function assignableEmployees(): Collection
    {
        return User::nonAdmin()->with('profile')->get();
    }

    private function colleagues(Request $request): Collection
    {
        return User::where('id', '!=', $request->user()->id)
            ->nonAdmin()
            ->orderBy('name')
            ->get();
    }
}
