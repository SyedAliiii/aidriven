<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .auth-bg{
                background:#f8f9fc;
                background-image:
                    radial-gradient(ellipse 600px 500px at 15% 10%, rgba(79,70,229,.10), transparent 60%),
                    radial-gradient(ellipse 500px 420px at 85% 90%, rgba(6,182,212,.10), transparent 60%);
            }
            .dark .auth-bg{
                background:#0d111c;
                background-image:
                    radial-gradient(ellipse 600px 500px at 15% 10%, rgba(79,70,229,.18), transparent 60%),
                    radial-gradient(ellipse 500px 420px at 85% 90%, rgba(6,182,212,.14), transparent 60%);
            }
            .auth-logo-tile{
                background:linear-gradient(135deg,#4f46e5,#7c3aed);
                box-shadow:0 8px 28px rgba(79,70,229,.35),0 0 0 1px rgba(165,180,252,.2);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased dark:bg-gray-950">
        <div class="auth-bg min-h-screen flex flex-col sm:justify-center items-center px-4 pt-10 sm:pt-0">

            <a href="/" class="flex items-center gap-2.5">
                <div class="auth-logo-tile flex h-12 w-12 items-center justify-center rounded-2xl">
                    <x-application-logo class="w-7 h-7 fill-current text-white" />
                </div>
                <span class="text-lg font-semibold tracking-tight text-gray-900 dark:text-gray-100">{{ config('app.name', 'Laravel') }}</span>
            </a>

            <div class="w-full sm:max-w-md mt-8 px-6 py-7 sm:px-8 sm:py-8 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm rounded-2xl">
                {{ $slot }}
            </div>

            <p class="mt-8 text-xs text-gray-400 dark:text-gray-600">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
            </p>
        </div>
    </body>
</html>