<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Browse Markets
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Search --}}
            <form method="GET"
                  action="{{ route('customer.markets.index') }}"
                  class="mb-6 flex flex-col sm:flex-row gap-3">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search markets by name or address..."
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                >

                <button
                    type="submit"
                    class="px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Search
                </button>

                <a
                    href="{{ route('customer.markets.index') }}"
                    class="px-5 py-2 border rounded-md text-center">
                    Reset
                </a>
            </form>

            {{-- Market Listing --}}
            @if ($markets->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach ($markets as $market)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $market->name }}
                            </h3>

                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                {{ $market->address }}
                            </p>

                            <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                                {{ $market->farmers->count() }}
                                approved farmer(s)
                            </p>

                            <div class="mt-5">
                                <a
                                    href="{{ route('customer.markets.show', $market->id) }}"
                                    class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    View Market
                                </a>
                            </div>

                            {{-- Favorite Market Button --}}
                            <div class="mt-3">
                                @if (in_array($market->id, $favoriteMarketIds))
                                    <form
                                        method="POST"
                                        action="{{ route('customer.favorites.markets.destroy', $market->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="px-4 py-2 border border-red-500 text-red-600 rounded-md hover:bg-red-50">
                                            Remove from Favorites
                                        </button>
                                    </form>
                                @else
                                    <form
                                        method="POST"
                                        action="{{ route('customer.favorites.markets.store', $market->id) }}">
                                        @csrf

                                        <button
                                            type="submit"
                                            class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-md hover:bg-indigo-50">
                                            Add to Favorites
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    @endforeach

                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $markets->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                    <p class="text-gray-600 dark:text-gray-300">
                        No markets found.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>