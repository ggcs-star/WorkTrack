<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WorkTrack') }}</title>

        <link rel="icon" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ mobileNavOpen: false }">
        @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp

        <div class="min-h-screen flex flex-col">
            <!-- Full-width top bar -->
            <header class="bg-white border-b border-gray-100 sticky top-0 z-30 relative">
                <!-- Search bar: absolutely centered on the full header width -->
                <div class="hidden sm:block absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md px-4 pointer-events-none">
                    <div class="relative pointer-events-auto">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <x-icon name="search" class="h-4 w-4" />
                        </span>
                        <input type="text" placeholder="Search tasks, employees..." disabled
                            class="block w-full pl-9 border-gray-200 bg-gray-50 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm text-gray-400 cursor-not-allowed">
                    </div>
                </div>

                <div class="flex items-center h-20">
                    <div class="flex items-center justify-center gap-2 px-4 lg:w-64 lg:shrink-0 lg:border-r lg:border-gray-100 h-full">
                        <button @click="mobileNavOpen = true" class="lg:hidden -ml-1 p-2 rounded-md text-gray-500 hover:bg-gray-100">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <a href="{{ url('/') }}" class="flex items-center gap-2">
                            <img src="{{ asset('images/sideLogo.jpeg') }}" alt="" class="h-14 w-14 object-contain rounded">
                            <span class="font-bold text-2xl"><span class="text-navy-700">Work</span><span class="text-sky-600">Track</span></span>
                        </a>
                    </div>

                    <div class="flex-1 flex items-center justify-end gap-4 min-w-0 px-4 sm:px-6">
                        <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-navy-700">
                            <x-icon name="bell" class="h-6 w-6" />
                            @if ($unreadCount > 0)
                                <span class="absolute top-1 right-1 h-4 w-4 flex items-center justify-center rounded-full bg-danger-500 text-white text-[10px] font-bold leading-none">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                                    <span class="h-8 w-8 rounded-full bg-navy-700 text-white flex items-center justify-center text-sm font-semibold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                    <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                                    <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </header>

            <div class="flex flex-1 min-h-0">
                @include('layouts.sidebar')

                <!-- Mobile sidebar drawer -->
                <div x-show="mobileNavOpen" x-cloak class="lg:hidden fixed inset-0 z-40">
                    <div class="fixed inset-0 bg-black/50" @click="mobileNavOpen = false"></div>
                    <div class="relative flex flex-col w-64 h-full bg-gradient-to-b from-navy-900 to-navy-800" @click.outside="mobileNavOpen = false">
                        <div class="px-5 py-4 flex items-center justify-between border-b border-navy-700">
                            <span class="text-white font-semibold">Menu</span>
                            <button @click="mobileNavOpen = false" class="text-navy-300 hover:text-white">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
                            @role('admin')
                                <x-sidebar-link href="{{ route('admin.dashboard') }}" icon="dashboard" :active="request()->routeIs('admin.dashboard')">Dashboard</x-sidebar-link>
                                <x-sidebar-link href="{{ route('admin.employees.index') }}" icon="users" :active="request()->routeIs('admin.employees.*')">Employees</x-sidebar-link>
                                <x-sidebar-link href="{{ route('admin.tasks.create') }}" icon="plus-circle" :active="request()->routeIs('admin.tasks.create')">Create Task</x-sidebar-link>
                                <x-sidebar-link href="{{ route('admin.tasks.index') }}" icon="clipboard" :active="request()->routeIs('admin.tasks.index') && ! in_array(request('status'), ['pending', 'completed', 'overdue'])">All Tasks</x-sidebar-link>
                                <x-sidebar-link href="{{ route('admin.tasks.index', ['status' => 'pending']) }}" icon="clock" :active="request()->routeIs('admin.tasks.index') && request('status') === 'pending'">Pending Tasks</x-sidebar-link>
                                <x-sidebar-link href="{{ route('admin.tasks.index', ['status' => 'completed']) }}" icon="check-circle" :active="request()->routeIs('admin.tasks.index') && request('status') === 'completed'">Completed Tasks</x-sidebar-link>
                                <x-sidebar-link href="{{ route('admin.tasks.index', ['status' => 'overdue']) }}" icon="overdue" :active="request()->routeIs('admin.tasks.index') && request('status') === 'overdue'">Overdue Tasks</x-sidebar-link>
                                <x-sidebar-link href="{{ route('admin.reports') }}" icon="chart" :active="request()->routeIs('admin.reports')">Reports</x-sidebar-link>
                                <x-sidebar-link href="{{ route('admin.settings') }}" icon="cog" :active="request()->routeIs('admin.settings')">Settings</x-sidebar-link>
                            @else
                                <x-sidebar-link href="{{ route('employee.dashboard') }}" icon="dashboard" :active="request()->routeIs('employee.dashboard')">Dashboard</x-sidebar-link>
                                <x-sidebar-link href="{{ route('employee.tasks.index') }}" icon="list" :active="request()->routeIs('employee.tasks.*')">My Tasks</x-sidebar-link>
                                <x-sidebar-link href="{{ route('notifications.index') }}" icon="bell" :active="request()->routeIs('notifications.*')">Notifications</x-sidebar-link>
                                <x-sidebar-link href="{{ route('profile.edit') }}" icon="user-circle" :active="request()->routeIs('profile.edit')">Profile</x-sidebar-link>
                            @endrole
                        </nav>
                        <div class="px-3 py-4 border-t border-navy-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-navy-200 hover:bg-navy-800 hover:text-white transition">
                                    <x-icon name="logout" class="h-5 w-5 shrink-0" />
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="flex-1 min-w-0 overflow-y-auto bg-sky-50">
                    @if (session('status'))
                        <div class="px-4 sm:px-6 pt-6">
                            <div class="rounded-md bg-success-50 border border-success-500 text-success-700 px-4 py-3 text-sm">
                                {{ session('status') }}
                            </div>
                        </div>
                    @endif

                    @if (isset($header))
                        <div class="px-4 sm:px-6 pt-6">
                            {{ $header }}
                        </div>
                    @endif

                    <!-- Page Content -->
                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
