<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Manage Customers</h1>

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
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $customer->name }}</td>
                        <td class="px-4 py-2">{{ $customer->email }}</td>
                        <td class="px-4 py-2">
                            {{ $customer->is_active ? 'Active' : 'Deactivated' }}
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            @if($customer->is_active)
                                <form action="{{ route('admin.customers.deactivate', $customer) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm px-3 py-1 bg-red-600 text-white rounded">
                                        Deactivate
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.customers.activate', $customer) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm px-3 py-1 bg-green-600 text-white rounded">
                                        Activate
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                            No customers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>
</x-app-layout>