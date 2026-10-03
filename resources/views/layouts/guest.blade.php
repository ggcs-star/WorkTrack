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
    <body class="font-sans text-gray-900 antialiased">
        <div class="h-screen flex overflow-hidden">
            <div class="hidden lg:block lg:w-1/2 relative">
                <img src="{{ asset('images/loginSidebar.jpeg') }}" alt="Manage Tasks, Boost Productivity" class="absolute inset-0 h-full w-full object-cover">
            </div>

            <div class="w-full lg:w-1/2 flex flex-col items-center justify-center px-6 py-4 overflow-y-auto">
                <div class="w-full max-w-md">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-28 md:h-32 w-auto mx-auto mb-6">

                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
