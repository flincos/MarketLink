<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Product Details
        </h2>
    </x-slot>

    @if ($errors->any())
    <div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">

                    {{-- Product Image --}}
                    <div>
                        @if ($product->image)
                            <img
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-80 object-cover rounded-lg">
                        @else
                            <div class="w-full h-80 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500 dark:text-gray-300">
                                    No product image available
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Product Information --}}
                    <div>

                        @if ($product->category)
                            <p class="text-sm text-indigo-600 font-medium">
                                {{ $product->category->name }}
                            </p>
                        @endif

                        <h1 class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $product->name }}
                        </h1>

                        <p class="mt-4 text-2xl font-bold text-indigo-600">
                            ₱{{ number_format((float) $product->price, 2) }}
                            <span class="text-base font-normal text-gray-500 dark:text-gray-400">
                                / {{ $product->unit }}
                            </span>
                        </p>

                        {{-- Availability --}}
                        <div class="mt-4">
                            @if ($product->is_available && $product->stock_quantity > 0)
                                <span class="inline-block px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                                    In Stock
                                </span>

                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    Available quantity: {{ $product->stock_quantity }}
                                </p>
                            @else
                                <span class="inline-block px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                                    Out of Stock
                                </span>
                            @endif
                        </div>

                        {{-- Description --}}
                        <div class="mt-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Description
                            </h2>

                            <p class="mt-2 text-gray-600 dark:text-gray-300 whitespace-pre-line">
                                {{ $product->description ?: 'No description available.' }}
                            </p>
                        </div>

                        {{-- Favorite Button --}}
                        <div class="mt-6">
                            @if ($isFavorite)
                                <form
                                    method="POST"
                                    action="{{ route('customer.favorites.products.destroy', $product->id) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="px-5 py-2 border border-red-500 text-red-600 rounded-md hover:bg-red-50">
                                        Remove from Favorites
                                    </button>
                                </form>
                            @else
                                <form
                                    method="POST"
                                    action="{{ route('customer.favorites.products.store', $product->id) }}">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        Add to Favorites
                                    </button>
                                </form>
                            @endif
                        </div>

                        {{-- Add to Cart --}}
                        @if ($product->is_available && $product->stock_quantity > 0)
                            <div class="mt-4">
                                <form method="POST" action="{{ route('customer.cart.store') }}" class="flex items-center gap-3">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <label class="text-sm text-gray-700 dark:text-gray-300">
                                        Quantity:
                                        <input
                                            type="number"
                                            name="quantity"
                                            value="1"
                                            min="1"
                                            max="{{ $product->stock_quantity }}"
                                            class="w-20 ml-1 border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1 text-sm dark:bg-gray-700 dark:text-gray-100"
                                            required
                                        >
                                    </label>

                                    <button
                                        type="submit"
                                        class="px-6 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700">
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>

            </div>

            {{-- Farmer Information --}}
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    Farmer Information
                </h2>

                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ $product->farmer->stall_name }}
                </h3>

                @if ($product->farmer->address)
                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                        {{ $product->farmer->address }}
                    </p>
                @endif

                @if ($product->farmer->description)
                    <p class="mt-3 text-gray-600 dark:text-gray-300">
                        {{ $product->farmer->description }}
                    </p>
                @endif

                <a
                    href="{{ route('customer.farmers.show', $product->farmer->id) }}"
                    class="inline-block mt-5 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    View Farmer Profile
                </a>

            </div>

                        {{-- Markets --}}
            @if ($product->farmer->markets->isNotEmpty())
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">

                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Markets Where This Farmer Operates
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                        @foreach ($product->farmer->markets as $market)
                            <div class="border rounded-lg p-4 dark:border-gray-700">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $market->name }}
                                </h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $market->address }}
                                </p>
                                <a
                                    href="{{ route('customer.markets.show', $market->id) }}"
                                    class="inline-block mt-3 text-indigo-600 hover:underline">
                                    View Market
                                </a>
                            </div>
                        @endforeach

                    </div>

                </div>
            @endif

            {{-- Customer Reviews --}}
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Customer Reviews
                </h2>

                @php
                    $reviews = $product->reviews()
                        ->where('is_hidden', false)
                        ->latest()
                        ->get();
                @endphp

                @if ($reviews->isEmpty())
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        There are no reviews for this product yet.
                    </p>
                @else
                    <div class="space-y-4">
                        @foreach ($reviews as $review)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-3 last:border-b-0">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $review->user->name ?? 'Customer' }}
                                    </span>
                                    <span class="text-sm text-yellow-500">
                                        Rating: {{ $review->rating }}/5
                                    </span>
                                </div>

                                @if ($review->comment)
                                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $review->comment }}
                                    </p>
                                @endif

                                @if ($review->farmer_response)
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        Farmer response: {{ $review->farmer_response }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Back to Products --}}
            <div class="mt-8">
                <a
                    href="{{ route('customer.products.index') }}"
                    class="text-indigo-600 hover:underline">
                    &larr; Back to Products
                </a>
            </div>

        </div>
    </div>
</x-app-layout>