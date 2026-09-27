<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Farmer Profile
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back Link --}}
            <div class="mb-6">
                <a
                    href="{{ route('customer.farmers.index') }}"
                    class="text-green-700 hover:text-green-900 dark:text-green-400"
                >
                    &larr; Back to Farmers
                </a>
            </div>

            {{-- Farmer Profile --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">

                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">

                    {{-- Farmer Information --}}
                    <div class="flex-1">

                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $farmer->stall_name }}
                        </h1>

                        @if ($farmer->description)
                            <p class="mt-4 text-gray-600 dark:text-gray-300">
                                {{ $farmer->description }}
                            </p>
                        @endif

                        <div class="mt-6 space-y-3 text-sm text-gray-600 dark:text-gray-300">

                            <p>
                                <span class="font-semibold">Contact Person:</span>
                                {{ $farmer->contact_person ?? 'Not provided' }}
                            </p>

                            <p>
                                <span class="font-semibold">Contact Number:</span>
                                {{ $farmer->contact_number ?? 'Not provided' }}
                            </p>

                            <p>
                                <span class="font-semibold">Address:</span>
                                {{ $farmer->address ?? 'Not provided' }}
                            </p>

                        </div>

                        {{-- Operating Days --}}
                        <div class="mt-6">

                            <h2 class="font-semibold text-gray-900 dark:text-gray-100">
                                Operating Days
                            </h2>

                            @if ($farmer->operating_days && count($farmer->operating_days) > 0)

                                <div class="mt-3 flex flex-wrap gap-2">

                                    @foreach ($farmer->operating_days as $day)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800">
                                            {{ $day }}
                                        </span>
                                    @endforeach

                                </div>

                            @else

                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Operating days have not been specified.
                                </p>

                            @endif

                        </div>

                    </div>

                    {{-- Farmer Favorite Button --}}
                    <div class="w-full md:w-64">

                        @if ($isFavorite)

                            <form
                                method="POST"
                                action="{{ route('customer.favorites.farmers.destroy', $farmer->id) }}"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="w-full rounded-md bg-red-600 px-5 py-3 font-medium text-white hover:bg-red-700 transition"
                                >
                                    Remove from Favorites
                                </button>
                            </form>

                        @else

                            <form
                                method="POST"
                                action="{{ route('customer.favorites.farmers.store', $farmer->id) }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="w-full rounded-md bg-green-600 px-5 py-3 font-medium text-white hover:bg-green-700 transition"
                                >
                                    Add to Favorites
                                </button>
                            </form>

                        @endif

                    </div>

                </div>

            </div>

            {{-- Farmer Products --}}
            <div class="mb-5 flex items-center justify-between">

                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    Products from {{ $farmer->stall_name }}
                </h2>

                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $farmer->products->count() }} product(s)
                </span>

            </div>

            @if ($farmer->products->isNotEmpty())

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                    @foreach ($farmer->products as $product)

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

                            {{-- Product Information --}}
                            <div class="p-5">

                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
    <a href="{{ route('customer.products.show', $product->id) }}"
       class="hover:text-green-600">
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

                                <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Stock:</span>
                                    {{ $product->stock_quantity }} {{ $product->unit }}
                                </p>

                                {{-- Availability --}}
                                <div class="mt-4">

                                    @if ($product->is_available && $product->stock_quantity > 0)

                                        <span class="inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                            In Stock
                                        </span>

                                    @else

                                        <span class="inline-block rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                            Out of Stock
                                        </span>

                                    @endif

                                </div>

                                {{-- Product Favorite Button --}}
                                <div class="mt-5">

                                    @if (in_array($product->id, $favoriteProductIds))

                                        <form
                                            method="POST"
                                            action="{{ route('customer.favorites.products.destroy', $product->id) }}"
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

                                    @else

                                        <form
                                            method="POST"
                                            action="{{ route('customer.favorites.products.store', $product->id) }}"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="w-full rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition"
                                            >
                                                Add to Favorites
                                            </button>
                                        </form>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-10 text-center">

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        No Products Available
                    </h3>

                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        This farmer has not listed any products yet.
                    </p>

                </div>

            @endif

        </div>
    </div>
</x-app-layout>