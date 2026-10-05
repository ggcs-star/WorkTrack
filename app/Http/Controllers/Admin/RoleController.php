<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\RoleLabel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $name = strtolower(trim($validated['name']));

        if ($name === '' || $name === 'admin') {
            return response()->json(['message' => 'That role name is not allowed.'], 422);
        }

        if (Role::whereRaw('LOWER(name) = ?', [$name])->exists()) {
            return response()->json(['message' => 'That role already exists.'], 422);
        }

        $role = Role::create(['name' => $name]);

        return response()->json([
            'name' => $role->name,
            'label' => RoleLabel::for($role->name),
        ], 201);
    }
}
