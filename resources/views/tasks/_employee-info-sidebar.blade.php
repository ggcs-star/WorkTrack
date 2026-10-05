<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-sm font-semibold text-sky-600 uppercase tracking-wide pb-2 border-b border-gray-200 mb-4">Employee Info</h3>

    <template x-if="selected">
        <div>
            <div class="flex items-center gap-3">
                <template x-if="selected.photo">
                    <img :src="selected.photo" alt="" class="h-12 w-12 rounded-full object-cover">
                </template>
                <template x-if="! selected.photo">
                    <span class="h-12 w-12 rounded-full bg-sky-600 text-white flex items-center justify-center text-base font-semibold" x-text="selected.name.charAt(0).toUpperCase()"></span>
                </template>
                <div>
                    <p class="font-medium text-gray-900" x-text="selected.name"></p>
                    <p class="text-xs text-gray-500" x-text="selected.designation || '—'"></p>
                </div>
            </div>

            <dl class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Department</dt>
                    <dd class="text-gray-900 text-right" x-text="selected.department || '—'"></dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Email</dt>
                    <dd class="text-gray-900 text-right truncate" x-text="selected.email"></dd>
                </div>
                <div class="flex justify-between gap-2">
                    <dt class="text-gray-500">Mobile</dt>
                    <dd class="text-gray-900 text-right" x-text="selected.mobile || '—'"></dd>
                </div>
            </dl>
        </div>
    </template>

    <template x-if="! selected">
        <p class="text-sm text-gray-400">Select an employee to see their details.</p>
    </template>
</div>
