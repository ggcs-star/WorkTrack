<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = User::whereHas('roles', fn ($q) => $q->whereIn('name', ['employee', 'hr']))
            ->with('roles')
            ->latest()
            ->paginate(15);

        return view('admin.employees.index', ['employees' => $employees]);
    }

    public function create(): View
    {
        return view('admin.employees.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['employee', 'hr'])],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.employees.index')->with('status', 'Account created for '.$user->name.'.');
    }

    public function edit(User $employee): View
    {
        return view('admin.employees.edit', ['employee' => $employee]);
    }

    public function update(Request $request, User $employee): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($employee->id)],
            'role' => ['required', Rule::in(['employee', 'hr'])],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $employee->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $employee->password,
        ]);

        $employee->syncRoles([$validated['role']]);

        return redirect()->route('admin.employees.index')->with('status', 'Updated '.$employee->name.'.');
    }

    public function destroy(User $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('status', 'Employee removed.');
    }

    public function toggleStatus(User $employee): RedirectResponse
    {
        $employee->update(['is_active' => ! $employee->is_active]);

        return redirect()->route('admin.employees.index')
            ->with('status', $employee->name.' is now '.($employee->is_active ? 'active' : 'inactive').'.');
    }
}
