<x-form-section title="Employment Details" icon="briefcase">
    <div>
        <x-input-label for="department" value="Department" />
        <x-text-input id="department" name="department" type="text" class="block mt-1 w-full" :value="old('department', $profile?->department)" />
        <x-input-error :messages="$errors->get('department')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="designation" value="Designation" />
        <x-text-input id="designation" name="designation" type="text" class="block mt-1 w-full" :value="old('designation', $profile?->designation)" />
        <x-input-error :messages="$errors->get('designation')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="reporting_manager_id" value="Reporting Manager" />
        <select id="reporting_manager_id" name="reporting_manager_id" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">
            <option value="">None</option>
            @foreach ($managers as $manager)
                <option value="{{ $manager->id }}" @selected((int) old('reporting_manager_id', $profile?->reporting_manager_id) === $manager->id)>{{ $manager->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('reporting_manager_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="joining_date" value="Joining Date" />
        <x-text-input id="joining_date" name="joining_date" type="date" class="block mt-1 w-full" :value="old('joining_date', $profile?->joining_date?->format('Y-m-d'))" />
        <x-input-error :messages="$errors->get('joining_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="employment_type" value="Employment Type" />
        <select id="employment_type" name="employment_type" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">
            <option value="">Select</option>
            @foreach (['Full-time', 'Part-time', 'Intern', 'Contract'] as $option)
                <option value="{{ $option }}" @selected(old('employment_type', $profile?->employment_type) === $option)>{{ $option }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('employment_type')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="work_location" value="Work Location" />
        <x-text-input id="work_location" name="work_location" type="text" class="block mt-1 w-full" :value="old('work_location', $profile?->work_location)" />
        <x-input-error :messages="$errors->get('work_location')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="shift" value="Shift" />
        <x-text-input id="shift" name="shift" type="text" class="block mt-1 w-full" :value="old('shift', $profile?->shift)" />
        <x-input-error :messages="$errors->get('shift')" class="mt-2" />
    </div>
</x-form-section>
