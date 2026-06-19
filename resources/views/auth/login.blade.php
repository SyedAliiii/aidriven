<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-950 min-h-screen flex items-center justify-center px-4 py-12">

<div class="w-full max-w-[400px]">

    {{-- Brand --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center h-11 w-11 rounded-2xl  mb-4">
            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center">
                <x-application-logo class="block h-20 w-auto fill-current text-gray-900 dark:text-white" />
            </a>
        </div>
        <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Welcome back</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sign in to your account to continue</p>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">

        {{-- Session Status --}}
        @if (session('status'))
            <div class="px-6 pt-5">
                <div class="rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/40 px-4 py-3 text-sm text-green-700 dark:text-green-300">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="px-6 py-6 space-y-5">
            @csrf

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

            {{-- Password --}}
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        Password
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium transition">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
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

            {{-- Remember Me --}}
            <label for="remember_me" class="flex items-center gap-2.5 cursor-pointer select-none w-fit">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0 bg-white dark:bg-gray-800"
                />
                <span class="text-sm text-gray-600 dark:text-gray-400">Remember me</span>
            </label>

            {{-- Submit --}}
            <button type="submit"
                class="w-full h-10 rounded-lg bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold tracking-wide transition shadow-sm shadow-indigo-200 dark:shadow-none">
                Sign in
            </button>
        </form>

        {{-- Divider + Register --}}
        @if (Route::has('register'))
            <div class="px-6 pb-5 border-t border-gray-100 dark:border-gray-800 pt-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 font-semibold transition ml-0.5">
                        Create one
                    </a>
                </p>
            </div>
        @endif

    </div>

</div>

</body>
</html>