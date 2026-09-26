<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Review Moderation</h1>

        @if(session('status'))
            <div class="mb-4 text-green-700 bg-green-100 border border-green-300 px-4 py-2 rounded">
                {{ session('status') }}
            </div>
        @endif

        <table class="min-w-full bg-white shadow rounded overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Product</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Customer</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Rating</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Comment</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Hidden?</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr class="border-t align-top">
                        <td class="px-4 py-2">
                            {{ optional($review->product)->name ?? '-' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ optional($review->user)->name ?? '-' }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $review->rating }}
                        </td>
                        <td class="px-4 py-2 text-sm max-w-xs">
                            {{ $review->comment }}
                        </td>
                        <td class="px-4 py-2">
                            {{ $review->is_hidden ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            @if(! $review->is_hidden)
                                <form action="{{ route('admin.reviews.hide', $review) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-sm px-3 py-1 bg-red-600 text-white rounded">
                                        Hide
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.reviews.unhide', $review) }}" method="POST" class="inline">
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
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                            No reviews found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    </div>
</x-app-layout>