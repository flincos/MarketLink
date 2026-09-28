<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Market Details
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Market Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                @php
                    $user = auth()->user();
                    $isPreferred = $user && $user->preferred_market_id === $market->id;
                @endphp

                <div class="mt-4">
                    @if ($isPreferred)
                        <form method="POST" action="{{ route('customer.markets.preferred.clear') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                                Remove Preferred Market
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('customer.markets.preferred.set', $market) }}">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                                Set as Preferred Market
                            </button>
                        </form>
                    @endif
                </div>

                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $market->name }}
                </h1>

                <p class="mt-3 text-gray-600 dark:text-gray-300">
                    {{ $market->address }}
                </p>
                @if($market->formattedOperatingDays())
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-semibold">Operating Days:</span> {{ $market->formattedOperatingDays() }}
                    </p>
                @endif

                @if($market->formattedOpeningTime() && $market->formattedClosingTime())
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-semibold">Timings:</span> {{ $market->formattedOpeningTime() }} – {{ $market->formattedClosingTime() }}
                    </p>
                @endif

                @if($market->latitude && $market->longitude)
                    <div class="mt-4 space-y-2">
                        {{-- Leaflet map with a single marker --}}
                        <x-map
                            id="market-map-{{ $market->id }}"
                            :height="'300px'"
                            :markers="[
                                [
                                    'lat' => $market->latitude,
                                    'lng' => $market->longitude,
                                    'label' => $market->name,
                                ],
                            ]"
                        />

                        {{-- External map link (OpenStreetMap) --}}
                        <a
                            href="https://www.openstreetmap.org/?mlat={{ $market->latitude }}&mlon={{ $market->longitude }}#map=16/{{ $market->latitude }}/{{ $market->longitude }}"
                            target="_blank"
                            rel="noopener"
                            class="inline-block text-indigo-600 dark:text-indigo-400 hover:underline text-sm"
                        >
                            Open in Map
                        </a>
                    </div>
                @endif
                @if($market->formattedOperatingDays())
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-semibold">Operating Days:</span> {{ $market->formattedOperatingDays() }}
                    </p>
                @endif

                @if($market->formattedOpeningTime() && $market->formattedClosingTime())
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        <span class="font-semibold">Timings:</span> {{ $market->formattedOpeningTime() }} – {{ $market->formattedClosingTime() }}
                    </p>
                @endif
                @if ($market->description)
                    <p class="mt-4 text-gray-700 dark:text-gray-300">
                        {{ $market->description }}
                    </p>
                @endif

                @if ($market->latitude && $market->longitude)
                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                        Coordinates:
                        {{ $market->latitude }},
                        {{ $market->longitude }}
                    </p>
                @endif

            </div>

            {{-- Farmers --}}
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Farmers at This Market
            </h2>

            @if ($market->farmers->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    @foreach ($market->farmers as $farmer)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $farmer->stall_name }}
                            </h3>

                            @if ($farmer->address)
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $farmer->address }}
                                </p>
                            @endif
                            @if($market->formattedOperatingDays())
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-semibold">Operating Days:</span> {{ $market->formattedOperatingDays() }}
                                </p>
                            @endif

                            @if($market->formattedOpeningTime() && $market->formattedClosingTime())
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-semibold">Timings:</span> {{ $market->formattedOpeningTime() }} – {{ $market->formattedClosingTime() }}
                                </p>
                            @endif

                            @if ($farmer->description)
                                <p class="mt-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $farmer->description }}
                                </p>
                            @endif

                            <div class="mt-5">
                                <a
                                    href="{{ route('customer.farmers.show', $farmer->id) }}"
                                    class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    View Farmer
                                </a>
                            </div>

                        </div>
                    @endforeach

                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <p class="text-gray-600 dark:text-gray-300">
                        No approved farmers are currently listed at this market.
                    </p>
                </div>
            @endif

            <div class="mt-6">
                <a
                    href="{{ route('customer.markets.index') }}"
                    class="text-indigo-600 hover:underline">
                    &larr; Back to Markets
                </a>
            </div>

        </div>
    </div>
</x-app-layout>