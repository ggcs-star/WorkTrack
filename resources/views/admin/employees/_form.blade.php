@php
$currentRole = $employee?->roles->first()->name;
$profile = $employee?->profile;
@endphp

<x-form-section title="Account Details" icon="lock">
    <div>
        <x-input-label for="name" value="Full Name" />
        <div class="relative mt-1">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <x-icon name="user-circle" class="h-5 w-5" />
            </span>
            <x-text-input id="name" name="name" type="text" class="block w-full pl-10" :value="old('name', $employee?->name)" required autofocus />
        </div>
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Login Email" />
        <div class="relative mt-1">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <x-icon name="mail" class="h-5 w-5" />
            </span>
            <x-text-input id="email" name="email" type="email" class="block w-full pl-10" :value="old('email', $employee?->email)" required />
        </div>
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div
        x-data="{
            roleValue: @js(old('role', $currentRole ?? '')),
            showAddRole: false,
            newRoleName: '',
            saving: false,
            error: '',
            onRoleChange(e) {
                if (e.target.value === '__add_new__') {
                    e.target.value = this.roleValue;
                    this.showAddRole = true;
                    this.$nextTick(() => this.$refs.newRoleInput.focus());
                } else {
                    this.roleValue = e.target.value;
                }
            },
            async saveRole() {
                if (! this.newRoleName.trim()) return;
                this.saving = true;
                this.error = '';
                try {
                    const res = await fetch('{{ route('admin.roles.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ name: this.newRoleName }),
                    });
                    const data = await res.json();
                    if (! res.ok) {
                        this.error = data.message || 'Could not add role.';
                        this.saving = false;
                        return;
                    }
                    this.$refs.addRoleOption.insertAdjacentHTML('beforebegin', `<option value=\'${data.name}\'>${data.label}</option>`);
                    this.$refs.roleSelect.value = data.name;
                    this.roleValue = data.name;
                    this.newRoleName = '';
                    this.showAddRole = false;
                } catch (err) {
                    this.error = 'Something went wrong. Please try again.';
                }
                this.saving = false;
            },
        }"
    >
        <x-input-label for="role" value="Role" />
        <div class="relative mt-1">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 z-10">
                <x-icon name="users" class="h-5 w-5" />
            </span>
            <select id="role" name="role" x-ref="roleSelect" @change="onRoleChange($event)" class="block w-full pl-10 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
                @foreach ($roles as $r)
                    <option value="{{ $r->name }}" @selected(old('role', $currentRole) === $r->name)>{{ $r->name === 'hr' ? 'HR' : \Illuminate\Support\Str::headline($r->name) }}</option>
                @endforeach
                <option value="__add_new__" x-ref="addRoleOption">+ Add New Role</option>
            </select>
        </div>
        <x-input-error :messages="$errors->get('role')" class="mt-2" />

        <div x-show="showAddRole" x-cloak class="mt-3 p-3 bg-sky-50 border border-sky-200 rounded-md space-y-2">
            <x-input-label value="New Role Name" />
            <div class="flex gap-2">
                <input type="text" x-ref="newRoleInput" x-model="newRoleName" @keydown.enter.prevent="saveRole()" placeholder="e.g. Manager"
                    class="block w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                <button type="button" @click="saveRole()" :disabled="saving"
                    class="inline-flex items-center px-3 py-2 bg-sky-600 rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-sky-700 disabled:opacity-50 shrink-0">
                    <span x-show="! saving">Save</span>
                    <span x-show="saving" x-cloak>Saving…</span>
                </button>
                <button type="button" @click="showAddRole = false; newRoleName = ''; error = ''"
                    class="inline-flex items-center px-3 py-2 bg-gray-100 rounded-md text-xs font-semibold text-gray-600 uppercase tracking-widest hover:bg-gray-200 shrink-0">
                    Cancel
                </button>
            </div>
            <p x-show="error" x-text="error" class="text-xs text-danger-600"></p>
        </div>
    </div>

    <div x-data="{ show: false }">
        <x-input-label for="password" :value="$employee ? 'New Password (leave blank to keep current)' : 'Temporary Password'" />
        <div class="relative mt-1">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <x-icon name="lock" class="h-5 w-5" />
            </span>
            <x-text-input id="password" name="password" class="block w-full pl-10 pr-10"
                type="password" x-bind:type="show ? 'text' : 'password'"
                :required="! $employee" />
            <button type="button" @click="show = ! show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                <x-icon name="eye" x-show="! show" class="h-5 w-5" />
                <x-icon name="eye-slash" x-show="show" x-cloak class="h-5 w-5" />
            </button>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>
</x-form-section>

@include('admin.employees._basic-details-fields')
@include('admin.employees._employment-details-fields')
@include('admin.employees._professional-details-fields')
@include('admin.employees._emergency-contact-fields')
