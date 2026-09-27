<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            My Favorites
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Introduction --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    My Favorites
                </h1>

                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Keep track of your favorite products, local farmers, and preferred markets.
                </p>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Product Favorites --}}
            <section class="mb-12">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-5">
                    Favorite Products
                </h2>

                @if ($productFavorites->isNotEmpty())

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                        @foreach ($productFavorites as $favorite)
                            @php
                                $product = $favorite->product;
                            @endphp

                            @if ($product)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

                                    {{-- Product Image --}}
                                    <div class="h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        @if ($product->image)
                                            <img
                                                src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover"
                                            >
                                        @else
                                            <span class="text-gray-400 text-sm">
                                                No image available
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Product Details --}}
                                    <div class="p-5">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $product->name }}
                                        </h3>

                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $product->category->name ?? 'Uncategorized' }}
                                        </p>

                                        <p class="mt-3 text-xl font-bold text-green-700 dark:text-green-400">
                                            ₱{{ number_format($product->price, 2) }}
                                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                                / {{ $product->unit }}
                                            </span>
                                        </p>

                                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                                            <span class="font-medium">Farmer:</span>
                                            {{ $product->farmer->stall_name ?? 'Unknown Farmer' }}
                                        </p>

                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                            <span class="font-medium">Stock:</span>
                                            {{ $product->stock_quantity }} {{ $product->unit }}
                                        </p>

                                        {{-- Remove Favorite --}}
                                        <form
                                            method="POST"
                                            action="{{ route('customer.favorites.products.destroy', $product->id) }}"
                                            class="mt-5"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="w-full rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition"
                                            >
                                                Remove from Favorites
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>

                @else
                    <div class="rounded-lg bg-white dark:bg-gray-800 p-8 text-center shadow">
                        <p class="text-gray-600 dark:text-gray-400">
                            You haven't saved any products yet.
                        </p>

                        <a
                            href="{{ route('customer.products.index') }}"
                            class="inline-block mt-4 rounded-md bg-green-600 px-5 py-2 text-white hover:bg-green-700 transition"
                        >
                            Browse Products
                        </a>
                    </div>
                @endif
            </section>

            {{-- Farmer Favorites --}}
            <section>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-5">
                    Favorite Farmers
                </h2>

                @if ($farmerFavorites->isNotEmpty())

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                        @foreach ($farmerFavorites as $favorite)
                            @php
                                $farmer = $favorite->farmer;
                            @endphp

                            @if ($farmer)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">

                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $farmer->stall_name }}
                                    </h3>

                                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                                        <span class="font-medium">Contact Person:</span>
                                        {{ $farmer->contact_person ?? 'Not provided' }}
                                    </p>

                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                        <span class="font-medium">Address:</span>
                                        {{ $farmer->address ?? 'Not provided' }}
                                    </p>

                                    @if ($farmer->operating_days)
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                            <span class="font-medium">Operating Days:</span>
                                            {{ implode(', ', $farmer->operating_days) }}
                                        </p>
                                    @endif

                                    {{-- Remove Favorite --}}
                                    <form
                                        method="POST"
                                        action="{{ route('customer.favorites.farmers.destroy', $farmer->id) }}"
                                        class="mt-5"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="w-full rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition"
                                        >
                                            Remove from Favorites
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endforeach

                    </div>

                @else
                    <div class="rounded-lg bg-white dark:bg-gray-800 p-8 text-center shadow">
                        <p class="text-gray-600 dark:text-gray-400">
                            You haven't saved any farmers yet.
                        </p>
                    </div>
                @endif
            </section>
             {{-- Preferred Markets --}}
<div class="mt-8">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
        My Preferred Markets
    </h2>

    @if ($marketFavorites->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach ($marketFavorites as $favorite)
                @if ($favorite->market)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $favorite->market->name }}
                        </h3>

                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            {{ $favorite->market->address }}
                        </p>

                        <div class="mt-5 flex flex-wrap gap-3">

                            <a
                                href="{{ route('customer.markets.show', $favorite->market->id) }}"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                View Market
                            </a>

                            <form
                                method="POST"
                                action="{{ route('customer.favorites.markets.destroy', $favorite->market->id) }}">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="px-4 py-2 border border-red-500 text-red-600 rounded-md hover:bg-red-50">
                                    Remove
                                </button>
                            </form>

                        </div>
                    </div>
                @endif
            @endforeach

        </div>
    @else
        <p class="text-gray-600 dark:text-gray-300">
            You haven't saved any preferred markets yet.
        </p>
    @endif
</div>

        </div>
        
    </div>
   
</x-app-layout>