<x-form-section title="Emergency Contact" icon="phone">
    <div>
        <x-input-label for="emergency_contact_name" value="Contact Person Name" />
        <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text" class="block mt-1 w-full" :value="old('emergency_contact_name', $profile?->emergency_contact_name)" />
        <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="emergency_contact_relationship" value="Relationship" />
        <x-text-input id="emergency_contact_relationship" name="emergency_contact_relationship" type="text" class="block mt-1 w-full" :value="old('emergency_contact_relationship', $profile?->emergency_contact_relationship)" />
        <x-input-error :messages="$errors->get('emergency_contact_relationship')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="emergency_contact_mobile" value="Mobile Number" />
        <x-text-input id="emergency_contact_mobile" name="emergency_contact_mobile" type="text" class="block mt-1 w-full" :value="old('emergency_contact_mobile', $profile?->emergency_contact_mobile)" />
        <x-input-error :messages="$errors->get('emergency_contact_mobile')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="emergency_contact_alternate_mobile" value="Alternate Number" />
        <x-text-input id="emergency_contact_alternate_mobile" name="emergency_contact_alternate_mobile" type="text" class="block mt-1 w-full" :value="old('emergency_contact_alternate_mobile', $profile?->emergency_contact_alternate_mobile)" />
        <x-input-error :messages="$errors->get('emergency_contact_alternate_mobile')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="emergency_contact_address" value="Address" />
        <textarea id="emergency_contact_address" name="emergency_contact_address" rows="2" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">{{ old('emergency_contact_address', $profile?->emergency_contact_address) }}</textarea>
        <x-input-error :messages="$errors->get('emergency_contact_address')" class="mt-2" />
    </div>
</x-form-section>
