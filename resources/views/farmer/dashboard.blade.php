<x-app-layout>
    <div class="p-6">

        @if (session('status'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if (isset($profile))
            <h1 class="text-xl font-bold mb-4">
                Welcome, {{ $profile->stall_name }}!
            </h1>

            @if ($profile->status === 'pending')
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-4">
                    Your farmer profile is waiting for approval.
                </div>
            @elseif ($profile->status === 'suspended')
                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                    Your account has been suspended, please contact customer support for appeal.
                </div>
            @endif
        @endif

        <div class="mt-6">
            <h2 class="text-xl font-bold mb-4">Farmer Dashboard</h2>

            <p class="text-gray-600 mb-6">
                Manage your products, markets, pickup slots and orders.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="bg-white p-6 rounded shadow">
                    <h3 class="text-gray-600 text-sm mb-2">Total Products</h3>
                    <div class="text-2xl font-bold">0</div>
                </div>

                <div class="bg-white p-6 rounded shadow">
                    <h3 class="text-gray-600 text-sm mb-2">Active Orders</h3>
                    <div class="text-2xl font-bold">0</div>
                </div>

                <div class="bg-white p-6 rounded shadow">
                    <h3 class="text-gray-600 text-sm mb-2">Markets</h3>
                    <div class="text-2xl font-bold">0</div>
                </div>

                <div class="bg-white p-6 rounded shadow">
                    <h3 class="text-gray-600 text-sm mb-2">Reviews</h3>
                    <div class="text-2xl font-bold">0</div>
                </div>

            </div>

            <div class="bg-white mt-6 p-6 rounded shadow">
                <h2 class="text-lg font-bold">Recent Orders</h2>

                <p class="mt-3 text-gray-600">
                    No orders yet.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>