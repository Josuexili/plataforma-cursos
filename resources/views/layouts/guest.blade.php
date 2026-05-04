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
    <body class="font-sans text-gray-900 antialiased">
        <div class="app-canvas min-h-screen flex flex-col items-center justify-center px-4 py-10">
            <div class="mb-6 text-center">
                <a href="/">
                    <x-application-logo class="mx-auto h-20 w-20 fill-current text-blue-900" />
                </a>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.28em] text-blue-700">Plataforma de cursos</p>
                <h1 class="mt-2 text-2xl font-semibold tracking-tight text-slate-950">Accés acadèmic</h1>
                <p class="mx-auto mt-2 max-w-md text-sm text-slate-600">Accedeix al teu espai per consultar cursos, seguir lliçons i gestionar la teva activitat dins la plataforma.</p>
            </div>

            <div class="institutional-panel w-full max-w-md overflow-hidden px-6 py-6 sm:px-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
