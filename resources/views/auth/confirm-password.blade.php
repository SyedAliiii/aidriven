<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm Password — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-950 min-h-screen flex items-center justify-center px-4 py-12">

<div class="w-full max-w-[400px]">

    {{-- Brand --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center h-11 w-11 rounded-2xl mb-4">
            <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center">
                <x-application-logo class="block h-20 w-auto fill-current text-gray-900 dark:text-white" />
            </a>
        </div>

        {{-- Shield Icon --}}
        <div class="inline-flex items-center justify-center h-14 w-14 rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/40 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-amber-500 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
            </svg>
        </div>

        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Confirm your password</h1>
        <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400 leading-relaxed max-w-[300px] mx-auto">
            This is a secure area. Please verify your password before continuing.
        </p>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">

        <form method="POST" action="{{ route('password.confirm') }}" class="px-6 py-6 space-y-5">
            @csrf

            {{-- Password --}}
            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Password
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autofocus
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="block w-full h-10 rounded-lg border px-3.5 text-sm transition
                        bg-white dark:bg-gray-800
                        text-gray-900 dark:text-gray-100
                        placeholder-gray-400 dark:placeholder-gray-500
                        border-gray-200 dark:border-gray-700
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                        @error('password') border-red-400 dark:border-red-600 @enderror"
                />
                @error('password')
                    <p class="text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full h-10 rounded-lg bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold tracking-wide transition shadow-sm shadow-indigo-200 dark:shadow-none">
                Confirm & Continue
            </button>
        </form>

        {{-- Back to dashboard --}}
        <div class="px-6 pb-5 border-t border-gray-100 dark:border-gray-800 pt-4 text-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Back to Dashboard
            </a>
        </div>

    </div>

</div>

</body>
</html>