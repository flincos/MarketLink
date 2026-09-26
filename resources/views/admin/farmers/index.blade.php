<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Manage Farmers</h1>

        @if(session('status'))
            <div class="mb-4 text-green-700 bg-green-100 border border-green-300 px-4 py-2 rounded">
                {{ session('status') }}
            </div>
        @endif

        <table class="min-w-full bg-white shadow rounded overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Stall</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($farmers as $farmer)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $farmer->user->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $farmer->user->email ?? '-' }}</td>
                        <td class="px-4 py-2">
                            {{ $farmer->stall_name ?? $farmer->business_name ?? '-' }}
                        </td>
                        <td class="px-4 py-2 capitalize">{{ $farmer->status }}</td>
                        <td class="px-4 py-2 space-x-2">
                            @if($farmer->status !== 'approved')
                                <form action="{{ route('admin.farmers.approve', $farmer) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="text-sm px-3 py-1 bg-green-600 text-white rounded"
                                        onclick="return confirm('Approve this farmer?')"
                                    >
                                        Approve
                                    </button>
                                </form>
                            @endif

                            @if($farmer->status !== 'suspended')
                                <form action="{{ route('admin.farmers.suspend', $farmer) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="text-sm px-3 py-1 bg-red-600 text-white rounded"
                                        onclick="return confirm('Suspend this farmer?')"
                                    >
                                        Suspend
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                            No farmers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $farmers->links() }}
        </div>
    </div>
</x-app-layout> 