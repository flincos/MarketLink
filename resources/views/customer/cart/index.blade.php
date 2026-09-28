<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Your Cart
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($items->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p class="text-gray-600 dark:text-gray-300">
                        Your cart is currently empty.
                    </p>

                    <a href="{{ route('customer.products.index') }}"
                       class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Browse Products
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Cart Items
                    </h3>

                    <div class="space-y-4">
                        @foreach ($items as $item)
                            @php
                                $product = $item['product'];
                            @endphp

                            <div class="flex items-start justify-between border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0">
                                <div class="flex items-start gap-4">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="w-20 h-20 object-cover rounded">
                                    @else
                                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center text-xs text-gray-400">
                                            No image
                                        </div>
                                    @endif

                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $product->name }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            ₱{{ number_format((float) $product->price, 2) }} / {{ $product->unit }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Farmer: {{ $product->farmer?->stall_name }}
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <form method="POST"
                                          action="{{ route('customer.cart.update', $product->id) }}"
                                          class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')

                                        <label class="text-xs text-gray-600 dark:text-gray-300">
                                            Qty:
                                            <input
                                                type="number"
                                                name="quantity"
                                                value="{{ $item['quantity'] }}"
                                                min="1"
                                                max="{{ $product->stock_quantity }}"
                                                class="w-16 ml-1 border border-gray-300 dark:border-gray-600 rounded px-1 py-0.5 text-xs dark:bg-gray-700 dark:text-gray-100"
                                                required
                                            >
                                        </label>

                                        <button type="submit"
                                                class="px-2 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                            Update
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('customer.cart.destroy', $product->id) }}"
                                          class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-xs text-red-600 hover:underline">
                                            Remove
                                        </button>
                                    </form>

                                    <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Subtotal:
                                        ₱{{ number_format((float) $item['subtotal'], 2) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <form method="POST" action="{{ route('customer.cart.clear') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-sm text-gray-600 dark:text-gray-300 hover:underline">
                                Clear cart
                            </button>
                        </form>

                        <div class="text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Total:
                            </p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                ₱{{ number_format((float) $total, 2) }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('customer.orders.create') }}"
                           class="px-6 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>