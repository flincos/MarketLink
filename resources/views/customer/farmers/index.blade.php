<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Discover Farmers
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Introduction --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    Discover Local Farmers
                </h1>

                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Find local farmers, explore their stalls, and save your favorites.
                </p>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Search and Filter --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">

                <form method="GET" action="{{ route('customer.farmers.index') }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        {{-- Search --}}
                        <div>
                            <label
                                for="search"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Search Farmers
                            </label>

                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ request('search') }}"
                                placeholder="Enter stall name..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >
                        </div>

                        {{-- Operating Day --}}
                        <div>
                            <label
                                for="day"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                            >
                                Operating Day
                            </label>

                            <select
                                name="day"
                                id="day"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-green-500 focus:ring-green-500"
                            >
                                <option value="">All Days</option>

                                @foreach ($days as $day)
                                    <option
                                        value="{{ $day }}"
                                        @selected(request('day') === $day)
                                    >
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-end gap-3">

                            <button
                                type="submit"
                                class="px-5 py-2.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition"
                            >
                                Search
                            </button>

                            <a
                                href="{{ route('customer.farmers.index') }}"
                                class="px-5 py-2.5 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>

            {{-- Results Header --}}
            <div class="mb-5 flex items-center justify-between">

                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Local Farmers
                </h2>

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $farmers->total() }} farmer(s) found
                </p>

            </div>

            {{-- Farmer Cards --}}
            @if ($farmers->count() > 0)

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                    @foreach ($farmers as $farmer)

                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">

                            {{-- Farmer Details --}}
                            <div class="p-5">

                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
    <a href="{{ route('customer.farmers.show', $farmer->id) }}"
       class="hover:text-indigo-600">
        {{ $farmer->stall_name }}
    </a>
</h3>   

                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Contact Person:</span>
                                    {{ $farmer->contact_person ?? 'Not provided' }}
                                </p>

                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Address:</span>
                                    {{ $farmer->address ?? 'Not provided' }}
                                </p>

                                @if ($farmer->contact_number)
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                        <span class="font-medium">Contact Number:</span>
                                        {{ $farmer->contact_number }}
                                    </p>
                                @endif

                                {{-- Operating Days --}}
                                <div class="mt-4">

                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Operating Days
                                    </p>

                                    @if ($farmer->operating_days)
                                        <div class="mt-2 flex flex-wrap gap-2">

                                            @foreach ($farmer->operating_days as $day)
                                                <span class="inline-block rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                                    {{ $day }}
                                                </span>
                                            @endforeach

                                        </div>
                                    @else
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Not specified
                                        </p>
                                    @endif

                                </div>

                                {{-- Farmer Description --}}
                                @if ($farmer->description)
                                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $farmer->description }}
                                    </p>
                                @endif

                                {{-- Favorite Button --}}
                                <div class="mt-5">

                                    @if (in_array($farmer->id, $favoriteFarmerIds))

                                        <form
                                            method="POST"
                                            action="{{ route('customer.favorites.farmers.destroy', $farmer->id) }}"
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
                                            action="{{ route('customer.favorites.farmers.store', $farmer->id) }}"
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

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $farmers->links() }}
                </div>

            @else

                {{-- Empty State --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-10 text-center">

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        No Farmers Found
                    </h3>

                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        No approved farmers match your search or selected operating day.
                    </p>

                    <a
                        href="{{ route('customer.farmers.index') }}"
                        class="inline-block mt-5 px-5 py-2.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition"
                    >
                        View All Farmers
                    </a>

                </div>

            @endif

        </div>
    </div>
</x-app-layout>