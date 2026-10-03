@php
$profile = $employee->profile;
$fullAddress = collect([$profile?->address, $profile?->city, $profile?->state, $profile?->pin_code])->filter()->implode(', ');
$tabs = [
    'overview' => 'Overview',
    'personal' => 'Personal Details',
    'employment' => 'Employment Details',
    'skills' => 'Skills & Qualifications',
    'documents' => 'Documents',
    'activity' => 'Activity Log',
];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <x-breadcrumb :items="['Dashboard' => route('admin.dashboard'), 'Employees' => route('admin.employees.index'), $employee->name => '']" />
                <h2 class="font-semibold text-xl text-navy-700 leading-tight">Employee Profile</h2>
                <p class="text-sm text-gray-500">View and manage employee details</p>
            </div>
            <a href="{{ route('admin.employees.edit', $employee) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700">
                <x-icon name="pencil" class="h-4 w-4" /> Edit
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <x-stat-card label="Total Tasks" :value="$taskCounts['total']" icon="clipboard" color="sky" />
                <x-stat-card label="Completed" :value="$taskCounts['completed']" icon="check-circle" color="success" />
                <x-stat-card label="Pending" :value="$taskCounts['pending']" icon="clock" color="warning" />
                <x-stat-card label="Overdue" :value="$taskCounts['overdue']" icon="overdue" color="danger" />
            </div>

            <div x-data="{ tab: 'overview' }" class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
                <!-- Left: profile card + tab nav -->
                <div class="lg:col-span-1 space-y-4">
                    <div class="bg-white rounded-xl shadow-sm p-6 text-center">
                        <x-avatar :name="$employee->name" :photo="$profile?->photo_path" size="20" class="mx-auto" />
                        <h3 class="mt-3 font-semibold text-gray-900">{{ $employee->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $profile?->designation ?: ucfirst($employee->roles->first()->name ?? '') }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2 {{ $employee->is_active ? 'bg-success-50 text-success-700 border border-success-500' : 'bg-gray-100 text-gray-500 border border-gray-300' }}">
                            {{ $employee->is_active ? 'Active' : 'Inactive' }}
                        </span>

                        <div class="mt-5 space-y-3 text-left text-sm">
                            <div class="flex items-center gap-2 text-gray-600">
                                <x-icon name="mail" class="h-4 w-4 text-gray-400 shrink-0" />
                                <span class="truncate">{{ $employee->email }}</span>
                            </div>
                            @if ($profile?->mobile_number)
                                <div class="flex items-center gap-2 text-gray-600">
                                    <x-icon name="phone" class="h-4 w-4 text-gray-400 shrink-0" />
                                    <span>{{ $profile->mobile_number }}</span>
                                </div>
                            @endif
                            @if ($profile?->city)
                                <div class="flex items-center gap-2 text-gray-600">
                                    <x-icon name="map-pin" class="h-4 w-4 text-gray-400 shrink-0" />
                                    <span>{{ collect([$profile->city, $profile->state])->filter()->implode(', ') }}</span>
                                </div>
                            @endif
                            <div class="flex items-center gap-2 text-gray-600">
                                <x-icon name="calendar" class="h-4 w-4 text-gray-400 shrink-0" />
                                <span>Joined on {{ ($profile?->joining_date ?? $employee->created_at)->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-2 space-y-1">
                        @foreach ($tabs as $key => $label)
                            <button @click="tab = '{{ $key }}'"
                                :class="tab === '{{ $key }}' ? 'bg-sky-600 text-white' : 'text-gray-600 hover:bg-gray-50'"
                                class="w-full text-left px-4 py-2.5 rounded-lg text-sm font-medium transition">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Right: tab content -->
                <div class="lg:col-span-3 space-y-6">
                    <div x-show="tab === 'overview'">
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-navy-900 uppercase tracking-wide pb-2 border-b border-gray-200">Basic Information</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4 mt-4 text-sm">
                                <x-detail-field label="Employee ID" :value="$profile?->employee_code" />
                                <x-detail-field label="Date of Birth" :value="$profile?->date_of_birth?->format('d M Y')" />
                                <x-detail-field label="Gender" :value="$profile?->gender" />
                                <x-detail-field label="Blood Group" :value="$profile?->blood_group" />
                                <x-detail-field label="Mobile Number" :value="$profile?->mobile_number" />
                                <x-detail-field label="Personal Email" :value="$profile?->personal_email" />
                            </dl>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                            <h3 class="text-sm font-semibold text-navy-900 uppercase tracking-wide pb-2 border-b border-gray-200">Address</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4 mt-4 text-sm">
                                <x-detail-field label="Address" :value="$fullAddress" class="sm:col-span-3" />
                            </dl>
                        </div>
                    </div>

                    <div x-show="tab === 'personal'" x-cloak>
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-navy-900 uppercase tracking-wide pb-2 border-b border-gray-200">Emergency Contact</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4 mt-4 text-sm">
                                <x-detail-field label="Contact Person" :value="$profile?->emergency_contact_name" />
                                <x-detail-field label="Relationship" :value="$profile?->emergency_contact_relationship" />
                                <x-detail-field label="Mobile Number" :value="$profile?->emergency_contact_mobile" />
                                <x-detail-field label="Alternate Number" :value="$profile?->emergency_contact_alternate_mobile" />
                                <x-detail-field label="Address" :value="$profile?->emergency_contact_address" class="sm:col-span-3" />
                            </dl>
                        </div>
                    </div>

                    <div x-show="tab === 'employment'" x-cloak>
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-navy-900 uppercase tracking-wide pb-2 border-b border-gray-200">Employment Details</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4 mt-4 text-sm">
                                <x-detail-field label="Department" :value="$profile?->department" />
                                <x-detail-field label="Designation" :value="$profile?->designation" />
                                <x-detail-field label="Reporting Manager" :value="$profile?->reportingManager?->name" />
                                <x-detail-field label="Joining Date" :value="$profile?->joining_date?->format('d M Y')" />
                                <x-detail-field label="Employment Type" :value="$profile?->employment_type" />
                                <x-detail-field label="Work Location" :value="$profile?->work_location" />
                                <x-detail-field label="Shift" :value="$profile?->shift" />
                            </dl>
                        </div>
                    </div>

                    <div x-show="tab === 'skills'" x-cloak>
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-navy-900 uppercase tracking-wide pb-2 border-b border-gray-200">Skills & Qualifications</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4 mt-4 text-sm">
                                <x-detail-field label="Qualification" :value="$profile?->qualification" />
                                <x-detail-field label="Specialization" :value="$profile?->specialization" />
                                <x-detail-field label="Total Experience" :value="$profile?->total_experience" />
                                <x-detail-field label="Previous Company" :value="$profile?->previous_company" />
                                <x-detail-field label="Previous Designation" :value="$profile?->previous_designation" />
                                <x-detail-field label="LinkedIn" :value="$profile?->linkedin_profile" />
                                <x-detail-field label="Skills" :value="$profile?->skills" class="sm:col-span-3" />
                                <x-detail-field label="Certifications" :value="$profile?->certifications" class="sm:col-span-3" />
                            </dl>
                        </div>
                    </div>

                    <div x-show="tab === 'documents'" x-cloak>
                        <div class="bg-white rounded-xl shadow-sm p-10 text-center">
                            <div class="mx-auto h-14 w-14 rounded-full bg-navy-100 text-navy-700 flex items-center justify-center mb-4">
                                <x-icon name="folder" class="h-7 w-7" />
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Coming Soon</h3>
                            <p class="text-sm text-gray-500 mt-1">Document uploads (ID proof, contracts, certificates) will appear here.</p>
                        </div>
                    </div>

                    <div x-show="tab === 'activity'" x-cloak>
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <h3 class="text-sm font-semibold text-navy-900 uppercase tracking-wide px-6 pt-6 pb-2 border-b border-gray-200">Recent Task Activity</h3>
                            <ul class="divide-y divide-gray-100">
                                @forelse ($recentTasks as $task)
                                    <li class="px-6 py-3 flex items-center justify-between">
                                        <div>
                                            <a href="{{ route('admin.tasks.show', $task) }}" class="text-sm font-medium text-gray-900 hover:text-sky-700">{{ $task->title }}</a>
                                            <p class="text-xs text-gray-400">Assigned by {{ $task->assigner->name ?? '—' }} &middot; Due {{ $task->due_date->format('d M Y') }}</p>
                                        </div>
                                        <x-status-badge :status="$task->effective_status" />
                                    </li>
                                @empty
                                    <li class="px-6 py-6 text-center text-sm text-gray-500">No tasks assigned yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
