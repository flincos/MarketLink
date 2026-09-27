<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Farmer Management') }}
            </h2>
            <a href="{{ route('admin.users.index') }}"
               class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                All Users →
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Status filter --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex flex-wrap gap-3 items-center">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Filter by status:</span>
                @foreach (['', 'pending', 'approved', 'suspended'] as $s)
                    @php
                        $label = $s === '' ? 'All' : ucfirst($s);
                        $active = request('status', '') === $s;
                        $colors = [
                            ''          => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
                            'pending'   => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300',
                            'approved'  => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300',
                            'suspended' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300',
                        ];
                    @endphp
                    <a href="{{ route('admin.farmers.index', $s ? ['status' => $s] : []) }}"
                       class="px-4 py-1.5 rounded-full text-sm font-medium transition
                              {{ $active ? 'ring-2 ring-offset-1 ring-current ' : 'opacity-70 hover:opacity-100 ' }}
                              {{ $colors[$s] }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Farmers Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                @if ($farmers->isEmpty())
                    <div class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        No farmers found.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stall Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contact Person</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registered</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($farmers as $farmer)
                                    @php
                                        $statusColors = [
                                            'pending'   => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'approved'  => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'suspended' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        ];
                                        $badgeClass = $statusColors[$farmer->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $farmer->stall_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                            {{ $farmer->contact_person }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                            {{ $farmer->user?->email ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                                {{ ucfirst($farmer->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                            {{ $farmer->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex items-center gap-2">
                                                @if ($farmer->status !== 'approved')
                                                    <form method="POST"
                                                          action="{{ route('admin.farmers.approve', $farmer) }}"
                                                          onsubmit="return confirm('Approve {{ addslashes($farmer->stall_name) }}?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition">
                                                            Approve
                                                        </button>
                                                    </form>
                                                @endif
                                                @if ($farmer->status !== 'suspended')
                                                    <form method="POST"
                                                          action="{{ route('admin.farmers.suspend', $farmer) }}"
                                                          onsubmit="return confirm('Suspend {{ addslashes($farmer->stall_name) }}?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition">
                                                            Suspend
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($farmers->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $farmers->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
