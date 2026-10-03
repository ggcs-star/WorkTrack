<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesEmployeeProfile;
use App\Http\Controllers\Controller;
use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    use ValidatesEmployeeProfile;

    public function index(Request $request): View
    {
        $base = User::whereHas('roles', fn ($q) => $q->where('name', '!=', 'admin'));

        $employees = (clone $base)
            ->with(['roles', 'profile'])
            ->when($request->filled('search'), fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', '%'.$request->input('search').'%')
                ->orWhere('email', 'like', '%'.$request->input('search').'%')))
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($request->filled('department'), fn ($q) => $q->whereHas('profile', fn ($q) => $q
                ->where('department', $request->input('department'))))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.employees.index', [
            'employees' => $employees,
            'totalCount' => (clone $base)->count(),
            'activeCount' => (clone $base)->where('is_active', true)->count(),
            'inactiveCount' => (clone $base)->where('is_active', false)->count(),
            'departments' => EmployeeProfile::whereNotNull('department')->distinct()->orderBy('department')->pluck('department'),
            'roles' => $this->assignableRoles(),
        ]);
    }

    public function create(): View
    {
        return view('admin.employees.create', [
            'managers' => $this->possibleManagers(),
            'roles' => $this->assignableRoles(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in($this->assignableRoles()->pluck('name'))],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        $profileData = $this->validateProfile($request);
        $profileData['photo_path'] = $this->storeProfilePhoto($request) ?? null;
        $profileData['user_id'] = $user->id;

        EmployeeProfile::create($profileData);

        return redirect()->route('admin.employees.index')->with('status', 'Account created for '.$user->name.'.');
    }

    public function show(User $employee): View
    {
        $taskCounts = [
            'total' => $employee->tasksAssignedToMe()->count(),
            'completed' => $employee->tasksAssignedToMe()->where('status', 'completed')->count(),
            'pending' => $employee->tasksAssignedToMe()->where('status', '!=', 'completed')->where('due_date', '>=', today())->count(),
            'overdue' => $employee->tasksAssignedToMe()->overdue()->count(),
        ];

        return view('admin.employees.show', [
            'employee' => $employee->load(['roles', 'profile.reportingManager']),
            'taskCounts' => $taskCounts,
            'recentTasks' => $employee->tasksAssignedToMe()->with('assigner')->latest()->take(10)->get(),
        ]);
    }

    public function edit(User $employee): View
    {
        return view('admin.employees.edit', [
            'employee' => $employee->load('profile'),
            'managers' => $this->possibleManagers($employee),
            'roles' => $this->assignableRoles(),
        ]);
    }

    public function update(Request $request, User $employee): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($employee->id)],
            'role' => ['required', Rule::in($this->assignableRoles()->pluck('name'))],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $employee->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $employee->password,
        ]);

        $employee->syncRoles([$validated['role']]);

        $profileData = $this->validateProfile($request);
        if ($photoPath = $this->storeProfilePhoto($request)) {
            $profileData['photo_path'] = $photoPath;
        }

        EmployeeProfile::updateOrCreate(['user_id' => $employee->id], $profileData);

        return redirect()->route('admin.employees.index')->with('status', 'Updated '.$employee->name.'.');
    }

    public function destroy(User $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('status', 'Employee removed.');
    }

    public function updateRole(Request $request, User $employee): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', Rule::in($this->assignableRoles()->pluck('name'))],
        ]);

        $employee->syncRoles([$validated['role']]);

        return redirect()->route('admin.employees.index')
            ->with('status', $employee->name.'\'s role updated to '.ucfirst($validated['role']).'.');
    }

    public function updateStatus(Request $request, User $employee): RedirectResponse
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $employee->update(['is_active' => $validated['is_active']]);

        return redirect()->route('admin.employees.index')
            ->with('status', $employee->name.' is now '.($employee->is_active ? 'active' : 'inactive').'.');
    }

    public function team(User $employee): View
    {
        abort_unless($employee->hasAnyRole(['manager', 'team leader']), 404);

        return view('admin.employees.team', [
            'manager' => $employee,
            'teamMembers' => $employee->teamMembers()->with(['roles', 'profile'])->orderBy('name')->get(),
        ]);
    }

    private function possibleManagers(?User $excluding = null)
    {
        return User::when($excluding, fn ($q) => $q->whereKeyNot($excluding->id))
            ->orderBy('name')
            ->get();
    }

    private function assignableRoles()
    {
        return Role::where('name', '!=', 'admin')->orderBy('name')->get();
    }
}
