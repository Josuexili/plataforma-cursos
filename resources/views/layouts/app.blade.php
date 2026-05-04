<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="app-canvas min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="relative z-10 border-b border-[#d6d1c7]/80 bg-white/70 backdrop-blur-sm">
                    <div class="max-w-7xl mx-auto px-4 py-7 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative z-0">
                @if (session('status'))
                    <div class="max-w-7xl mx-auto px-4 pt-6 sm:px-6 lg:px-8">
                        <div class="rounded-xl border border-emerald-200/90 bg-emerald-50/92 px-4 py-3 text-sm font-medium text-emerald-800 shadow-sm">
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </body>
</html>
