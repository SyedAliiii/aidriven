<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    Organizations
                </h1>

                <a href="{{ route('admin.organizations.create') }}"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-gray-900 text-white dark:bg-white dark:text-gray-900 hover:opacity-90 transition">
                    Add Organization
                </a>
            </div>

            @if (session('success'))
                <div class="mt-4 rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm dark:bg-green-900/30 dark:border-green-900/60 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mt-4 rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm dark:bg-red-900/30 dark:border-red-900/60 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mt-6 overflow-x-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">DB</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Port</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach($organizations as $org)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $org->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $org->db_host }}<br />
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $org->db_database }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $org->db_port }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($org->status === 'active')
                                        <span class="inline-flex px-2 py-1 rounded text-xs bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-200 dark:border-green-900/60">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 rounded text-xs bg-gray-100 text-gray-700 border border-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.organizations.edit', $org) }}"
                                            class="px-3 py-1.5 rounded-md border border-gray-200 dark:border-gray-700 text-sm text-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.organizations.test-connection', $org) }}">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1.5 rounded-md bg-indigo-600 text-white text-sm hover:opacity-90 transition">
                                                Test Connection
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.organizations.destroy', $org) }}"
                                            onsubmit="return confirm('Delete this organization?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1.5 rounded-md bg-red-600 text-white text-sm hover:opacity-90 transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $organizations->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

