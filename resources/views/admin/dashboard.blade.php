<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-500 text-sm">Total Farmers</div>
                <div class="text-3xl font-semibold">{{ $totalFarmers }}</div>
            </div>

            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-500 text-sm">Pending Farmers</div>
                <div class="text-3xl font-semibold">{{ $pendingFarmers }}</div>
            </div>

            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-500 text-sm">Total Customers</div>
                <div class="text-3xl font-semibold">{{ $totalCustomers }}</div>
            </div>

            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-500 text-sm">Total Markets</div>
                <div class="text-3xl font-semibold">{{ $totalMarkets }}</div>
            </div>

            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-500 text-sm">Total Orders</div>
                <div class="text-3xl font-semibold">{{ $totalOrders }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white shadow rounded p-6">
                <h2 class="text-lg font-semibold mb-4">
                    Revenue Summary Across Markets
                </h2>

                @forelse ($marketRevenue as $entry)
                    <div class="flex justify-between border-b py-3 last:border-b-0">
                        <span>
                            {{ $entry->market->name ?? 'Unknown Market' }}
                        </span>

                        <span class="font-semibold">
                            {{ number_format((float) $entry->revenue, 2) }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500">No completed order revenue yet.</p>
                @endforelse

                <div class="flex justify-between pt-4 mt-2 border-t font-semibold">
                    <span>Total Revenue</span>
                    <span>{{ number_format((float) $revenueTotal, 2) }}</span>
                </div>
            </div>

            <div class="bg-white shadow rounded p-6">
                <h2 class="text-lg font-semibold mb-4">
                    Most Active Farmers
                </h2>

                @forelse ($activeFarmers as $farmer)
                    <div class="flex justify-between border-b py-3 last:border-b-0">
                        <span>
                            {{ $farmer->stall_name }}
                        </span>

                        <span class="font-semibold">
                            {{ $farmer->orders_count }} orders
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500">No farmer order activity yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
