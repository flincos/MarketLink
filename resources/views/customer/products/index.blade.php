<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Browse Products
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Introduction --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    Fresh Products
                </h1>

                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Discover fresh products from local farmers.
                </p>
            </div>

            {{-- Search and Category Filter --}}
            <form method="GET"
      action="{{ route('customer.products.index') }}"
      class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    {{-- Search --}}
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search products..."
        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
    >

    {{-- Category Filter --}}
    <select
        name="category"
        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">

        <option value="">All Categories</option>

        @foreach ($categories as $category)
            <option
                value="{{ $category->id }}"
                {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    {{-- Market Filter --}}
    <select
        name="market"
        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">

        <option value="">All Markets</option>

        @foreach ($markets as $market)
            <option
                value="{{ $market->id }}"
                {{ request('market') == $market->id ? 'selected' : '' }}>
                {{ $market->name }}
            </option>
        @endforeach
    </select>

    {{-- Operating Day Filter --}}
    <select
        name="day"
        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">

        <option value="">All Days</option>

        @foreach ($days as $day)
            <option
                value="{{ $day }}"
                {{ request('day') == $day ? 'selected' : '' }}>
                {{ $day }}
            </option>
        @endforeach
    </select>
    {{-- Availability Filter --}}
<select
    name="availability"
    class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">

    <option value="">All Availability</option>

    <option
        value="in_stock"
        {{ request('availability') === 'in_stock' ? 'selected' : '' }}>
        In Stock
    </option>

    <option
        value="out_of_stock"
        {{ request('availability') === 'out_of_stock' ? 'selected' : '' }}>
        Out of Stock
    </option>
</select>

    <div class="sm:col-span-2 lg:col-span-4 flex flex-wrap gap-3">
        <button
            type="submit"
            class="px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            Apply Filters
        </button>

        <a
            href="{{ route('customer.products.index') }}"
            class="px-5 py-2 border rounded-md text-center">
            Reset
        </a>
    </div>
</form>
            {{-- Product Count --}}
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Available Products
                </h2>

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $products->total() }} product(s) found
                </p>
            </div>

            {{-- Product Listing --}}
            @if ($products->count() > 0)

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                    @foreach ($products as $product)

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
    <a
        href="{{ route('customer.products.show', $product->id) }}"
        class="hover:text-indigo-600">
        {{ $product->name }}
    </a>
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

                                <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">

                                    <p>
                                        <span class="font-medium">Farmer:</span>
                                        {{ $product->farmer->stall_name ?? 'Unknown Farmer' }}
                                    </p>

                                    <p>
                                        <span class="font-medium">Stock:</span>
                                        {{ $product->stock_quantity }} {{ $product->unit }}
                                    </p>

                                </div>

                                {{-- Availability --}}
                                <div class="mt-4">
                                    @if ($product->is_available && $product->stock_quantity > 0)
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            In Stock
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </div>

                    @endforeach

                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $products->links() }}
                </div>

            @else

                {{-- Empty State --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-10 text-center">

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        No Products Found
                    </h3>

                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        No products match your selected filters.
                    </p>

                    <a
                        href="{{ route('customer.products.index') }}"
                        class="inline-block mt-5 px-5 py-2.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition"
                    >
                        View All Products
                    </a>

                </div>

            @endif

        </div>
    </div>
</x-app-layout>