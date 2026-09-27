<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Order Details
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg">
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Order Info Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">

                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Order #{{ $order->id }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Placed on {{ $order->created_at->format('F d, Y \a\t g:i A') }}
                        </p>
                    </div>

                    {{-- Status Badge --}}
                    @php
                        $badgeClass = match($order->status) {
                            'placed'    => 'bg-blue-100 text-blue-800',
                            'accepted'  => 'bg-green-100 text-green-800',
                            'ready'     => 'bg-purple-100 text-purple-800',
                            'completed' => 'bg-gray-100 text-gray-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            'declined'  => 'bg-red-100 text-red-800',
                            default     => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full {{ $badgeClass }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Pickup Date</p>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ $order->pickup_date?->format('F d, Y') ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Pickup Time</p>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ $order->pickup_time ? \Carbon\Carbon::parse($order->pickup_time)->format('g:i A') : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Market</p>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ $order->market?->name ?? '—' }}
                        </p>
                    </div>
                    @if ($order->notes)
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Notes</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Farmer Info Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Farmer</h4>
                <p class="text-gray-900 dark:text-gray-100 font-medium">
                    {{ $order->farmer?->stall_name ?? '—' }}
                </p>
                @if ($order->farmer?->contact_person)
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        Contact: {{ $order->farmer->contact_person }}
                    </p>
                @endif
                @if ($order->farmer?->contact_number)
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        Phone: {{ $order->farmer->contact_number }}
                    </p>
                @endif
            </div>

            {{-- Order Items Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Order Items</h4>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Qty</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Unit Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $item->product_name }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                        ₱{{ number_format((float) $item->price, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-indigo-600">
                                        ₱{{ number_format((float) $item->subtotal, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Total Amount
                                </td>
                                <td class="px-4 py-3 text-sm font-bold text-indigo-600">
                                    ₱{{ number_format((float) $order->total_amount, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Cancel Button --}}
            @if ($order->status === 'placed')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">Cancel Order</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        You can cancel this order as it has not yet been accepted by the farmer.
                    </p>
                    <form method="POST" action="{{ route('customer.orders.cancel', $order->id) }}"
                          onsubmit="return confirm('Are you sure you want to cancel this order?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Cancel Order
                        </button>
                    </form>
                </div>
            @endif

            {{-- Review Section (only if completed) --}}
            @if ($order->status === 'completed')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Leave a Review</h4>

                    @foreach ($order->items as $item)
                        @if ($item->product_id)
                            <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                                <p class="font-medium text-gray-900 dark:text-gray-100 mb-3">
                                    {{ $item->product_name }}
                                </p>

                                @if (isset($existingReviews[$item->product_id]))
                                    <p class="text-sm text-green-600">
                                        ✓ You have already reviewed this product.
                                    </p>
                                @else
                                    <form method="POST" action="{{ route('customer.reviews.store') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                                        {{-- Star Rating --}}
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Rating
                                            </label>
                                            <div class="flex gap-4">
                                                @for ($star = 1; $star <= 5; $star++)
                                                    <label class="flex items-center gap-1 cursor-pointer">
                                                        <input type="radio" name="rating" value="{{ $star }}"
                                                               class="text-indigo-600 focus:ring-indigo-500" required>
                                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $star }} ★</span>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>

                                        {{-- Comment --}}
                                        <div class="mb-3">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Comment <span class="text-gray-400">(optional)</span>
                                            </label>
                                            <textarea name="comment" rows="3" maxlength="1000"
                                                      placeholder="Share your experience..."
                                                      class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                                        </div>

                                        <button type="submit"
                                                class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                                            Submit Review
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- Back Link --}}
            <div class="mt-4">
                <a href="{{ route('customer.orders.index') }}"
                   class="text-indigo-600 hover:underline">
                    ← Back to My Orders
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
