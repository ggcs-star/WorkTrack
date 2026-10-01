@php
$currentRole = $employee?->roles->first()->name;
@endphp

<div>
    <x-input-label for="name" value="Full Name" />
    <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" :value="old('name', $employee?->name)" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div>
    <x-input-label for="email" value="Email" />
    <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" :value="old('email', $employee?->email)" required />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<div>
    <x-input-label for="role" value="Role" />
    <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
        <option value="employee" @selected(old('role', $currentRole) === 'employee')>Employee</option>
        <option value="hr" @selected(old('role', $currentRole) === 'hr')>HR</option>
    </select>
    <x-input-error :messages="$errors->get('role')" class="mt-2" />
</div>

<div>
    <x-input-label for="password" :value="$employee ? 'New Password (leave blank to keep current)' : 'Temporary Password'" />
    <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" {{ $employee ? '' : 'required' }} />
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>
