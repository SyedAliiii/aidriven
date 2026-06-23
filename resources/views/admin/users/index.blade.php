<x-app-layout>
<div class="px-4 py-6 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    {{-- Page Header --}}
    <div class="mb-6 flex items-start justify-between gap-3">
        <div>
            <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Users</h1>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Manage accounts, organizations, and roles.</p>
        </div>
        <button onclick="document.getElementById('create-panel').classList.toggle('hidden')"
            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            New User
        </button>
    </div>

    {{-- Success Notice --}}
    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800/40 dark:bg-green-900/20 dark:text-green-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Create User Panel --}}
    <div id="create-panel" class="hidden mb-6">
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-gray-200 uppercase tracking-wider">Create New User</h2>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}" class="px-4 py-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Name</label>
                        <input type="text" name="name" required placeholder="Jane Smith"
                            class="h-9 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Email</label>
                        <input type="email" name="email" required placeholder="jane@example.com"
                            class="h-9 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="h-9 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Organization</label>
                        <select name="organization_id"
                            class="h-9 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5 sm:col-span-2">
                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Role</label>
                        <select name="role"
                            class="h-9 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-100 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">No role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('create-panel').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Users — MOBILE: card view, DESKTOP: table --}}

    {{-- Mobile card list (hidden on sm+) --}}
    <div class="sm:hidden space-y-3">
        @forelse($users as $user)
            @php $roleName = $user->roles->pluck('name')->first(); @endphp
            <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
                {{-- User info --}}
                <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                    <div class="h-9 w-9 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-sm font-semibold text-indigo-600 dark:text-indigo-400 uppercase shrink-0">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $user->email }}</p>
                    </div>
                    <div class="shrink-0">
                        @if($roleName)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800/50">
                                {{ $roleName }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                No role
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Organization + actions --}}
                <div class="px-4 py-3">
                    @if($user->organization)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 flex items-center gap-1.5">
                            <span class="h-1.5 w-1.5 rounded-full bg-indigo-400 shrink-0"></span>
                            {{ $user->organization->name }}
                        </p>
                    @else
                        <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">No organization</p>
                    @endif

                    {{-- Edit form --}}
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">
                        <input type="hidden" name="password" value="">
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <select name="organization_id"
                                class="h-9 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-700 dark:text-gray-200 px-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                @foreach($organizations as $org)
                                    <option value="{{ $org->id }}" @selected($user->organization_id === $org->id)>{{ $org->name }}</option>
                                @endforeach
                            </select>
                            <select name="role"
                                class="h-9 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-700 dark:text-gray-200 px-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                <option value="">No role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" @selected($user->roles->pluck('name')->first() === $role->name)>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="flex-1 h-9 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium transition">
                                Save
                            </button>
                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                onsubmit="return confirm('Delete {{ $user->name }}?')" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="h-9 w-9 inline-flex items-center justify-center rounded-lg border border-red-200 dark:border-red-800/40 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 py-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300 dark:text-gray-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="text-sm text-gray-400 dark:text-gray-500">No users yet. Create one above.</p>
            </div>
        @endforelse

        @if($users->hasPages())
            <div class="pt-2">{{ $users->links() }}</div>
        @endif
    </div>

    {{-- Desktop table (hidden on mobile) --}}
    <div class="hidden sm:block rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/50">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Organization</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($users as $user)
                        <tr class="group hover:bg-gray-50/60 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase shrink-0">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($user->organization)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-700 dark:text-gray-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-400"></span>
                                        {{ $user->organization->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @php $roleName = $user->roles->pluck('name')->first(); @endphp
                                @if($roleName)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800/50">
                                        {{ $roleName }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400">No role</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="name" value="{{ $user->name }}">
                                        <input type="hidden" name="email" value="{{ $user->email }}">
                                        <input type="hidden" name="password" value="">
                                        <select name="organization_id"
                                            class="h-8 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-700 dark:text-gray-200 px-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                            @foreach($organizations as $org)
                                                <option value="{{ $org->id }}" @selected($user->organization_id === $org->id)>{{ $org->name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="role"
                                            class="h-8 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-700 dark:text-gray-200 px-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                            <option value="">No role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" @selected($user->roles->pluck('name')->first() === $role->name)>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="h-8 px-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium transition">Save</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-flex"
                                        onsubmit="return confirm('Delete {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="h-8 w-8 inline-flex items-center justify-center rounded-lg border border-red-200 dark:border-red-800/40 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">No users yet. Create one above.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">{{ $users->links() }}</div>
        @endif
    </div>

</div>
</x-app-layout>