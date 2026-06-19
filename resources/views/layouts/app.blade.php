<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ config('app.name') }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        <script>
            (function () {
                const theme = localStorage.getItem('theme');
                const preferDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                const isDark = theme === 'dark' || (!theme && preferDark);
                document.documentElement.classList.toggle('dark', isDark);
            })();
        </script>

        @filamentStyles
        @vite('resources/css/app.css')
    </head>

    <body class="antialiased bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100">
        <div class="min-h-screen bg-gray-50 dark:bg-gray-950">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="min-h-[calc(100vh-3.5rem)]">
                {{ $slot }}
            </main>
        </div>

        @livewire('notifications')

        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>