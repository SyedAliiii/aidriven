<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Page header --}}
            <div class="flex items-start gap-3">
                <div class="hidden sm:flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Account</p>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Profile</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage your account information, password, and security.
                    </p>
                </div>
            </div>

            {{-- Sections --}}
            <div class="mt-6 space-y-5">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="rounded-xl border border-red-100 bg-white p-6 shadow-sm dark:border-red-900/40 dark:bg-gray-900">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>