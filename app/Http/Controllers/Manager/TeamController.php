<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function edit(Request $request): View
    {
        $manager = $request->user();

        $candidates = User::whereHas('roles', fn ($q) => $q->whereNotIn('name', ['admin', 'manager']))
            ->where('id', '!=', $manager->id)
            ->with(['roles', 'profile'])
            ->orderBy('name')
            ->get();

        $teamMembers = $manager->teamMembers()
            ->with(['roles', 'profile'])
            ->when($request->filled('search'), fn ($q) => $q->where(fn ($q) => $q
                ->where('users.name', 'like', '%'.$request->input('search').'%')
                ->orWhere('users.email', 'like', '%'.$request->input('search').'%')))
            ->when($request->filled('department'), fn ($q) => $q->whereHas('profile', fn ($q) => $q
                ->where('department', $request->input('department'))))
            ->orderBy('users.name')
            ->paginate(15)
            ->withQueryString();

        return view('manager.team.edit', [
            'candidates' => $candidates,
            'teamMembers' => $teamMembers,
            'teamMemberIds' => $manager->teamMembers()->pluck('users.id')->toArray(),
            'departments' => EmployeeProfile::whereNotNull('department')->distinct()->orderBy('department')->pluck('department'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'members' => ['array'],
            'members.*' => ['exists:users,id'],
        ]);

        $request->user()->teamMembers()->sync($validated['members'] ?? []);

        return redirect()->route('employee.team.edit')->with('status', 'Team updated successfully.');
    }
}
