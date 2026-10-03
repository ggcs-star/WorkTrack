<x-form-section title="Basic Employee Details" icon="user-circle">
    <div>
        <x-input-label for="employee_code" value="Employee ID / Code" />
        <x-text-input id="employee_code" name="employee_code" type="text" class="block mt-1 w-full" :value="old('employee_code', $profile?->employee_code)" />
        <x-input-error :messages="$errors->get('employee_code')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="date_of_birth" value="Date of Birth" />
        <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="block mt-1 w-full" :value="old('date_of_birth', $profile?->date_of_birth?->format('Y-m-d'))" />
        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="gender" value="Gender" />
        <select id="gender" name="gender" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">
            <option value="">Select</option>
            @foreach (['Male', 'Female', 'Other'] as $option)
                <option value="{{ $option }}" @selected(old('gender', $profile?->gender) === $option)>{{ $option }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="blood_group" value="Blood Group" />
        <select id="blood_group" name="blood_group" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">
            <option value="">Select</option>
            @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $option)
                <option value="{{ $option }}" @selected(old('blood_group', $profile?->blood_group) === $option)>{{ $option }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('blood_group')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="mobile_number" value="Mobile Number" />
        <x-text-input id="mobile_number" name="mobile_number" type="text" class="block mt-1 w-full" :value="old('mobile_number', $profile?->mobile_number)" />
        <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="personal_email" value="Personal Email" />
        <x-text-input id="personal_email" name="personal_email" type="email" class="block mt-1 w-full" :value="old('personal_email', $profile?->personal_email)" />
        <x-input-error :messages="$errors->get('personal_email')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="photo" value="Profile Photo" />
        @if ($profile?->photo_path)
            <img src="{{ asset('storage/'.$profile->photo_path) }}" alt="" class="h-16 w-16 rounded-full object-cover mt-1 mb-2">
        @endif
        <input id="photo" name="photo" type="file" accept="image/*"
            class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-navy-50 file:text-navy-700 hover:file:bg-navy-100">
        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="address" value="Address" />
        <textarea id="address" name="address" rows="2" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">{{ old('address', $profile?->address) }}</textarea>
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="city" value="City" />
        <x-text-input id="city" name="city" type="text" class="block mt-1 w-full" :value="old('city', $profile?->city)" />
        <x-input-error :messages="$errors->get('city')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="state" value="State" />
        <x-text-input id="state" name="state" type="text" class="block mt-1 w-full" :value="old('state', $profile?->state)" />
        <x-input-error :messages="$errors->get('state')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="pin_code" value="PIN Code" />
        <x-text-input id="pin_code" name="pin_code" type="text" class="block mt-1 w-full" :value="old('pin_code', $profile?->pin_code)" />
        <x-input-error :messages="$errors->get('pin_code')" class="mt-2" />
    </div>
</x-form-section>
