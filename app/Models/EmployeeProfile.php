<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeProfile extends Model
{
    protected $fillable = [
        'user_id',
        'employee_code',
        'photo_path',
        'date_of_birth',
        'gender',
        'mobile_number',
        'personal_email',
        'blood_group',
        'address',
        'city',
        'state',
        'pin_code',
        'department',
        'designation',
        'reporting_manager_id',
        'joining_date',
        'employment_type',
        'work_location',
        'shift',
        'skills',
        'qualification',
        'specialization',
        'total_experience',
        'previous_company',
        'previous_designation',
        'certifications',
        'linkedin_profile',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_mobile',
        'emergency_contact_alternate_mobile',
        'emergency_contact_address',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reportingManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporting_manager_id');
    }
}
