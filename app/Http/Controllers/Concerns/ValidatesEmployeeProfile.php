<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

trait ValidatesEmployeeProfile
{
    private function validateProfile(Request $request): array
    {
        return $request->validate([
            'employee_code' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'mobile_number' => ['nullable', 'string', 'max:30'],
            'personal_email' => ['nullable', 'email', 'max:255'],
            'blood_group' => ['nullable', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'pin_code' => ['nullable', 'string', 'max:20'],

            'department' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'reporting_manager_id' => ['nullable', 'exists:users,id'],
            'joining_date' => ['nullable', 'date'],
            'employment_type' => ['nullable', Rule::in(['Full-time', 'Part-time', 'Intern', 'Contract'])],
            'work_location' => ['nullable', 'string', 'max:255'],
            'shift' => ['nullable', 'string', 'max:255'],

            'skills' => ['nullable', 'string'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'total_experience' => ['nullable', 'string', 'max:255'],
            'previous_company' => ['nullable', 'string', 'max:255'],
            'previous_designation' => ['nullable', 'string', 'max:255'],
            'certifications' => ['nullable', 'string'],
            'linkedin_profile' => ['nullable', 'url', 'max:255'],

            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:255'],
            'emergency_contact_mobile' => ['nullable', 'string', 'max:30'],
            'emergency_contact_alternate_mobile' => ['nullable', 'string', 'max:30'],
            'emergency_contact_address' => ['nullable', 'string'],
        ]);
    }

    private function storeProfilePhoto(Request $request): ?string
    {
        if (! $request->hasFile('photo')) {
            return null;
        }

        $request->validate(['photo' => ['image', 'max:2048']]);

        return $request->file('photo')->store('employee-photos', 'public');
    }
}
