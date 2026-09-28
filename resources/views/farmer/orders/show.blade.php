<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Order #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Order Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-3">
                    <strong class="text-gray-900 dark:text-gray-100">Order Information</strong>
                </div>

                <div class="px-6 py-4 space-y-2 text-sm">
                    <p>
                        <strong class="text-gray-700 dark:text-gray-300">Customer:</strong>
                        <span class="text-gray-900 dark:text-gray-100">
                            {{ $order->customer->name ?? 'N/A' }}
                        </span>
                    </p>

                    <p>
                        <strong class="text-gray-700 dark:text-gray-300">Market:</strong>
                        <span class="text-gray-900 dark:text-gray-100">
                            {{ $order->market->name ?? 'N/A' }}
                        </span>
                    </p>

                    <p>
                        <strong class="text-gray-700 dark:text-gray-300">Pickup Date:</strong>
                        <span class="text-gray-900 dark:text-gray-100">
                            {{ $order->pickup_date?->format('d M Y') }}
                        </span>
                    </p>

                    <p>
                        <strong class="text-gray-700 dark:text-gray-300">Pickup Time:</strong>
                        <span class="text-gray-900 dark:text-gray-100">
                            {{ $order->pickup_time }}
                        </span>
                    </p>

                    <p>
                        <strong class="text-gray-700 dark:text-gray-300">Total Amount:</strong>
                        <span class="text-gray-900 dark:text-gray-100">
                            ₱{{ number_format((float) $order->total_amount, 2) }}
                        </span>
                    </p>

                    <p>
                        <strong class="text-gray-700 dark:text-gray-300">Status:</strong>
                        <span class="text-gray-900 dark:text-gray-100">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </p>

                    @if($order->notes)
                        <p>
                            <strong class="text-gray-700 dark:text-gray-300">Notes:</strong>
                            <span class="text-gray-900 dark:text-gray-100">
                                {{ $order->notes }}
                            </span>
                        </p>
                    @endif
                </div>
            </div>

            {{-- Ordered Products --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-3">
                    <strong class="text-gray-900 dark:text-gray-100">Ordered Products</strong>
                </div>

                <div class="px-6 py-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left">Product</th>
                                <th class="px-4 py-2 text-right">Price</th>
                                <th class="px-4 py-2 text-right">Quantity</th>
                                <th class="px-4 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100">
                                        {{ $item->product_name }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-200">
                                        ₱{{ number_format((float) $item->price, 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-200">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-gray-900 dark:text-gray-100">
                                        ₱{{ number_format((float) $item->subtotal, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Order Status Actions --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow px-6 py-4">
                <strong class="block mb-3 text-gray-900 dark:text-gray-100">
                    Order Status
                </strong>

                @if($order->status === 'placed')
                    <form method="POST"
                          action="{{ route('farmer.orders.update-status', $order) }}"
                          class="inline-block mr-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                            Accept Order
                        </button>
                    </form>

                    <form method="POST"
                          action="{{ route('farmer.orders.update-status', $order) }}"
                          class="inline-block">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="declined">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                            Decline Order
                        </button>
                    </form>

                @elseif($order->status === 'accepted')
                    <form method="POST"
                          action="{{ route('farmer.orders.update-status', $order) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="ready">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                            Mark Ready for Pickup
                        </button>
                    </form>

                @elseif($order->status === 'ready')
                    <form method="POST"
                          action="{{ route('farmer.orders.update-status', $order) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                            Mark Completed
                        </button>
                    </form>

                @else
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-0">
                        No further action available for this order.
                    </p>
                @endif
            </div>

            <div class="mt-6">
                <a href="{{ route('farmer.orders.index') }}"
                   class="text-sm text-indigo-600 hover:underline">
                    &larr; Back to Orders
                </a>
            </div>

        </div>
    </div>
</x-app-layout>