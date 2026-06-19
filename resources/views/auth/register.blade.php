<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create account — {{ config('app.name') }}</title>
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
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Create your account</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started — it only takes a minute</p>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">

        <form method="POST" action="{{ route('register') }}" class="px-6 py-6 space-y-5">
            @csrf

            {{-- Name --}}
            <div class="space-y-1.5">
                <label for="name" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Full Name
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="John Doe"
                    class="block w-full h-10 rounded-lg border px-3.5 text-sm transition
                        bg-white dark:bg-gray-800
                        text-gray-900 dark:text-gray-100
                        placeholder-gray-400 dark:placeholder-gray-500
                        border-gray-200 dark:border-gray-700
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                        @error('name') border-red-400 dark:border-red-600 @enderror"
                />
                @error('name')
                    <p class="text-xs text-red-500 dark:text-red-400 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
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
                    autocomplete="new-password"
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

            {{-- Confirm Password --}}
            <div class="space-y-1.5">
                <label for="password_confirmation" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Confirm Password
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="block w-full h-10 rounded-lg border px-3.5 text-sm transition
                        bg-white dark:bg-gray-800
                        text-gray-900 dark:text-gray-100
                        placeholder-gray-400 dark:placeholder-gray-500
                        border-gray-200 dark:border-gray-700
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                        @error('password_confirmation') border-red-400 dark:border-red-600 @enderror"
                />
                @error('password_confirmation')
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
                Create account
            </button>
        </form>

        {{-- Already registered --}}
        <div class="px-6 pb-5 border-t border-gray-100 dark:border-gray-800 pt-4 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Already have an account?
                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 font-semibold transition ml-0.5">
                    Sign in
                </a>
            </p>
        </div>

    </div>

</div>

</body>
</html>