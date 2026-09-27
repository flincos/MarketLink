<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
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
    </div>
</x-app-layout>