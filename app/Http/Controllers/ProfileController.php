<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ValidatesEmployeeProfile;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use ValidatesEmployeeProfile;

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'managers' => $user->hasRole('admin') ? collect() : User::whereKeyNot($user->id)->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the authenticated user's own employee profile details.
     */
    public function updateEmployeeDetails(Request $request): RedirectResponse
    {
        $profileData = $this->validateProfile($request);
        if ($photoPath = $this->storeProfilePhoto($request)) {
            $profileData['photo_path'] = $photoPath;
        }

        EmployeeProfile::updateOrCreate(['user_id' => $request->user()->id], $profileData);

        return Redirect::route('profile.edit')->with('status', 'Employee details updated successfully.');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'Profile updated successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
