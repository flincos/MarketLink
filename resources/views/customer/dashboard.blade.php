<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Customer Dashboard
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Welcome Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    Welcome back, {{ auth()->user()->name }}!
                </h1>

                <p class="mt-2 text-gray-600 dark:text-gray-300">
                    Discover local farmers, browse products, and explore your preferred markets.
                </p>

                <div class="mt-4">
                    <a
                        href="{{ route('customer.notifications.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">

                        Notifications

                        @if ($unreadNotificationsCount > 0)
                            <span class="bg-white text-indigo-700 text-xs font-bold rounded-full px-2 py-1">
                                {{ $unreadNotificationsCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

                <a
                    href="{{ route('customer.products.index') }}"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-md transition">

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Browse Products
                    </h3>

                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Discover fresh products from local farmers.
                    </p>
                </a>

                <a
                    href="{{ route('customer.farmers.index') }}"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-md transition">

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Find Farmers
                    </h3>

                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Explore approved farmers and their stalls.
                    </p>
                </a>

                <a
                    href="{{ route('customer.markets.index') }}"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-md transition">

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Explore Markets
                    </h3>

                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Find markets and discover farmers operating there.
                    </p>
                </a>

                <a
                    href="{{ route('customer.favorites.index') }}"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-md transition">

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        My Favorites
                    </h3>

                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        View your saved products, farmers, and markets.
                    </p>
                </a>

            </div>

            {{-- Favorite Products --}}
            <section class="mb-10">

                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        My Favorite Products
                    </h2>

                    <a
                        href="{{ route('customer.favorites.index') }}"
                        class="text-indigo-600 hover:underline">
                        View All Favorites
                    </a>
                </div>

                @if ($productFavorites->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                        @foreach ($productFavorites as $favorite)
                            @if ($favorite->product)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">

                                    @if ($favorite->product->image)
                                        <img
                                            src="{{ asset('storage/' . $favorite->product->image) }}"
                                            alt="{{ $favorite->product->name }}"
                                            class="w-full h-40 object-cover rounded-md mb-4">
                                    @endif

                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $favorite->product->name }}
                                    </h3>

                                    @if ($favorite->product->category)
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $favorite->product->category->name }}
                                        </p>
                                    @endif

                                    <p class="mt-2 text-lg font-bold text-indigo-600">
                                        ₱{{ number_format((float) $favorite->product->price, 2) }}
                                        <span class="text-sm font-normal text-gray-500">
                                            / {{ $favorite->product->unit }}
                                        </span>
                                    </p>

                                    @if ($favorite->product->farmer)
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                            {{ $favorite->product->farmer->stall_name }}
                                        </p>
                                    @endif

                                    <a
                                        href="{{ route('customer.favorites.index') }}"
                                        class="inline-block mt-4 text-indigo-600 hover:underline">
                                        Manage Favorites
                                    </a>

                                </div>
                            @endif
                        @endforeach

                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <p class="text-gray-600 dark:text-gray-300">
                            You haven't saved any favorite products yet.
                        </p>

                        <a
                            href="{{ route('customer.products.index') }}"
                            class="inline-block mt-3 text-indigo-600 hover:underline">
                            Browse Products
                        </a>
                    </div>
                @endif

            </section>

            {{-- Preferred Markets --}}
            <section>

                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        My Preferred Markets
                    </h2>

                    <a
                        href="{{ route('customer.favorites.index') }}"
                        class="text-indigo-600 hover:underline">
                        View All Favorites
                    </a>
                </div>

                @if ($marketFavorites->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                        @foreach ($marketFavorites as $favorite)
                            @if ($favorite->market)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">

                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $favorite->market->name }}
                                    </h3>

                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $favorite->market->address }}
                                    </p>

                                    <a
                                        href="{{ route('customer.markets.show', $favorite->market->id) }}"
                                        class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        View Market
                                    </a>

                                </div>
                            @endif
                        @endforeach

                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <p class="text-gray-600 dark:text-gray-300">
                            You haven't saved any preferred markets yet.
                        </p>

                        <a
                            href="{{ route('customer.markets.index') }}"
                            class="inline-block mt-3 text-indigo-600 hover:underline">
                            Explore Markets
                        </a>
                    </div>
                @endif

            </section>

        </div>
    </div>
</x-app-layout>