<x-form-section title="Professional Details" icon="academic-cap">
    <div class="sm:col-span-2">
        <x-input-label for="skills" value="Skills" />
        <textarea id="skills" name="skills" rows="2" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">{{ old('skills', $profile?->skills) }}</textarea>
        <x-input-error :messages="$errors->get('skills')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="qualification" value="Qualification" />
        <x-text-input id="qualification" name="qualification" type="text" class="block mt-1 w-full" :value="old('qualification', $profile?->qualification)" />
        <x-input-error :messages="$errors->get('qualification')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="specialization" value="Specialization" />
        <x-text-input id="specialization" name="specialization" type="text" class="block mt-1 w-full" :value="old('specialization', $profile?->specialization)" />
        <x-input-error :messages="$errors->get('specialization')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="total_experience" value="Total Experience" />
        <x-text-input id="total_experience" name="total_experience" type="text" class="block mt-1 w-full" placeholder="e.g. 3 years" :value="old('total_experience', $profile?->total_experience)" />
        <x-input-error :messages="$errors->get('total_experience')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="previous_company" value="Previous Company" />
        <x-text-input id="previous_company" name="previous_company" type="text" class="block mt-1 w-full" :value="old('previous_company', $profile?->previous_company)" />
        <x-input-error :messages="$errors->get('previous_company')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="previous_designation" value="Previous Designation" />
        <x-text-input id="previous_designation" name="previous_designation" type="text" class="block mt-1 w-full" :value="old('previous_designation', $profile?->previous_designation)" />
        <x-input-error :messages="$errors->get('previous_designation')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="certifications" value="Certifications" />
        <textarea id="certifications" name="certifications" rows="2" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">{{ old('certifications', $profile?->certifications) }}</textarea>
        <x-input-error :messages="$errors->get('certifications')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="linkedin_profile" value="LinkedIn Profile" />
        <x-text-input id="linkedin_profile" name="linkedin_profile" type="url" class="block mt-1 w-full" placeholder="https://linkedin.com/in/..." :value="old('linkedin_profile', $profile?->linkedin_profile)" />
        <x-input-error :messages="$errors->get('linkedin_profile')" class="mt-2" />
    </div>
</x-form-section>
