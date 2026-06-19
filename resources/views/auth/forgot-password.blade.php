<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password — {{ config('app.name') }}</title>
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

        {{-- Lock Icon --}}
        <div class="inline-flex items-center justify-center h-14 w-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800/40 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>

        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Forgot your password?</h1>
        <p class="mt-1.5 text-sm text-gray-500 dark:text-gray-400 leading-relaxed max-w-[300px] mx-auto">
            No problem. Enter your email and we'll send you a reset link.
        </p>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">

        {{-- Session Status --}}
        @if (session('status'))
            <div class="px-6 pt-5">
                <div class="rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/40 px-4 py-3 flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600 dark:text-green-400 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-700 dark:text-green-300">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="px-6 py-6 space-y-5">
            @csrf

            {{-- Email --}}
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Email Address
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                    class="block w-full h-10 rounded-lg border px-3.5 text-sm transition
                        bg-white dark:bg-gray-800
                        text-gray-900 dark:text-gray-100
                        placeholder-gray-400 dark:placeholder-gray-500
                        border-gray-200 dark:border-gray-700
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                        @error('email') border-red-400 dark:border-red-600 @enderror"
                />
                @error('email')
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
                Send Reset Link
            </button>
        </form>

        {{-- Back to login --}}
        <div class="px-6 pb-5 border-t border-gray-100 dark:border-gray-800 pt-4 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Back to Sign in
            </a>
        </div>

    </div>

</div>

</body>
</html>