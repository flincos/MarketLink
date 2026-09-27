<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Markets</h1>
            <a href="{{ route('admin.markets.create') }}"
               class="px-4 py-2 bg-green-600 text-white text-sm rounded">
                + New Market
            </a>
        </div>

        @if(session('status'))
            <div class="mb-4 text-green-700 bg-green-100 border border-green-300 px-4 py-2 rounded">
                {{ session('status') }}
            </div>
        @endif

        <table class="min-w-full bg-white shadow rounded overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Address</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Description</th>
                    <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($markets as $market)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $market->name }}</td>
                        <td class="px-4 py-2 text-sm">{{ $market->address }}</td>
                        <td class="px-4 py-2 text-sm">
                            {{ \Illuminate\Support\Str::limit($market->description, 60) }}
                        </td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <a href="{{ route('admin.markets.edit', $market) }}"
                               class="text-sm px-3 py-1 bg-blue-600 text-white rounded">
                                Edit
                            </a>

                            <form action="{{ route('admin.markets.destroy', $market) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Delete this market?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm px-3 py-1 bg-red-600 text-white rounded">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                            No markets found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $markets->links() }}
        </div>
    </div>
</x-app-layout>