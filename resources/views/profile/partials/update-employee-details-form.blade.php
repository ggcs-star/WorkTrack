<section>
    <header>
        <h2 class="text-lg font-semibold text-navy-900">
            {{ __('Employee Details') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __('Keep your HR profile up to date — this information is visible to your admin.') }}
        </p>
    </header>

    @php $profile = $user->profile; @endphp

    <form method="post" action="{{ route('profile.update-employee-details') }}" enctype="multipart/form-data" class="mt-4 space-y-2">
        @csrf
        @method('patch')

        @include('admin.employees._basic-details-fields')
        @include('admin.employees._employment-details-fields')
        @include('admin.employees._professional-details-fields')
        @include('admin.employees._emergency-contact-fields')

        <div class="flex items-center gap-4 pt-6">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <x-icon name="check-circle" class="h-4 w-4" /> {{ __('Save Details') }}
            </button>
        </div>
    </form>
</section>
