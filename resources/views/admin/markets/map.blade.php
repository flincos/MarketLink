<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Markets Map</h1>
            <a href="{{ route('admin.markets.index') }}"
               class="text-sm text-gray-600">
                &larr; Back to Markets
            </a>
        </div>

        <x-map id="markets-map" :height="'500px'" :markers="$markers" />

        @if(empty($markers))
            <p class="mt-4 text-gray-500 text-sm">
                No markets with coordinates found. Add latitude and longitude to markets to see them here.
            </p>
        @endif
    </div>
</x-app-layout>