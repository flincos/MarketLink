<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Product Moderation</h1>

        @if(session('status'))
            <div class="mb-4 text-green-700 bg-green-100 border border-green-300 px-4 py-2 rounded">
                {{ session('status') }}
            </div>
        @endif

        <table class="min-w-full bg-white shadow rounded overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Product</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Farmer</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Price</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Hidden?</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $product->name }}</td>
                        <td class="px-4 py-2">
                            {{ optional(optional($product->farmer)->user)->name ?? '-' }}
                        </td>
                        <td class="px-4 py-2">{{ $product->price }}</td>
                        <td class="px-4 py-2">
                            {{ $product->is_hidden ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            @if(! $product->is_hidden)
                                <form action="{{ route('admin.products.hide', $product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm px-3 py-1 bg-red-600 text-white rounded">
                                        Hide
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.products.unhide', $product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm px-3 py-1 bg-green-600 text-white rounded">
                                        Unhide
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>