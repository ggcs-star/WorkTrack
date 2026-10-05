@props(['task', 'action', 'colleagues'])

@php
    $displayStyle = \App\Models\Task::STATUS_STYLES[$task->effective_status]['select'];
@endphp

<div x-data="{
        showModal: false,
        pendingStatus: '',
        dependsOn: '',
        open(status, selectEl) {
            this.pendingStatus = status;
            this.dependsOn = '';
            this._select = selectEl;
            this.showModal = true;
        },
        cancel() {
            if (this._select) { this._select.value = this._select.dataset.original; }
            this.showModal = false;
        },
    }">
    <form method="POST" action="{{ $action }}">
        @csrf
        @method('PATCH')
        <select name="status" data-original="{{ $task->status }}"
            @change="
                if (['dependency', 'need_clarification'].includes($event.target.value)) {
                    open($event.target.value, $event.target);
                } else {
                    $event.target.closest('form').submit();
                }
            "
            class="text-xs font-medium rounded-full pl-2.5 pr-6 py-1 border cursor-pointer focus:outline-none focus:ring-2 focus:ring-sky-500 {{ $displayStyle }}">
            @foreach (\App\Models\Task::STATUSES as $statusOption)
                <option value="{{ $statusOption }}" @selected($task->status === $statusOption)>{{ \App\Models\Task::STATUS_STYLES[$statusOption]['label'] }}</option>
            @endforeach
        </select>
    </form>

    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto px-4 py-6" @keydown.escape.window="cancel()">
        <div class="fixed inset-0 bg-black/50" @click="cancel()"></div>

        <div class="relative bg-white rounded-xl shadow-xl max-w-md mx-auto overflow-hidden" @click.outside="cancel()">
            <form method="POST" action="{{ $action }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" :value="pendingStatus">

                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-navy-900" x-text="pendingStatus === 'dependency' ? 'Flag as Dependency' : 'Request Clarification'"></h3>

                    <template x-if="pendingStatus === 'dependency'">
                        <div>
                            <x-input-label value="Who is this blocked on?" />
                            <select name="depends_on_user_id" x-model="dependsOn" required
                                class="mt-1 block w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                                <option value="">Select employee</option>
                                @foreach ($colleagues as $colleague)
                                    <option value="{{ $colleague->id }}">{{ $colleague->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </template>

                    <div>
                        <x-input-label value="Reason" />
                        <textarea name="reason" rows="3" required placeholder="Explain what's blocking this task..."
                            class="mt-1 block w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 p-4 bg-gray-50 border-t border-gray-100">
                    <x-secondary-button type="button" @click="cancel()">{{ __('Cancel') }}</x-secondary-button>
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <x-icon name="check-circle" class="h-4 w-4" /> {{ __('Submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
