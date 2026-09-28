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
                class="mb-6 flex flex-col sm:flex-row flex-wrap gap-3">

                {{-- Search input --}}
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search markets by name or address..."
                    class="w-full sm:flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                >

                {{-- Market Day filter --}}
                <div class="w-full sm:w-64">
                    <label for="day" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Market Day
                    </label>
                    <select
                        name="day"
                        id="day"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                    >
                        <option value="">All days</option>
                        @foreach($weekdayOptions as $code => $label)
                            <option value="{{ $code }}" {{ ($selectedDay ?? '') === $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Apply
                    </button>

                    <a
                        href="{{ route('customer.markets.index') }}"
                        class="px-5 py-2 border rounded-md text-center">
                        Reset
                    </a>
                </div>
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

                            {{-- Operating days & timings --}}
                            @if($market->formattedOperatingDays())
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="font-semibold">Days:</span> {{ $market->formattedOperatingDays() }}
                                </p>
                            @endif

                            @if($market->formattedOpeningTime() && $market->formattedClosingTime())
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $market->formattedOpeningTime() }} – {{ $market->formattedClosingTime() }}
                                </p>
                            @endif

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