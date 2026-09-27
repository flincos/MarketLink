<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Platform Overview</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Farmers</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalFarmers }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Customers</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalCustomers }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Markets</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalMarkets }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalOrders }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pending Farmers</p>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingFarmers }}</p>
                        @if ($pendingFarmers > 0)
                            <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-xs font-semibold rounded-full">
                                Action needed
                            </span>
                        @endif
                    </div>
                    @if ($pendingFarmers > 0)
                        <a href="{{ route('admin.farmers.index', ['status' => 'pending']) }}"
                           class="text-xs text-red-600 dark:text-red-400 hover:underline mt-1 block">Review now →</a>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Revenue</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
                        ₱{{ number_format($revenueTotal, 2) }}
                    </p>
                </div>

            </div>

            {{-- Recent Orders --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h2>
                </div>
                @if ($recentOrders->isEmpty())
                    <div class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">No orders yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Farmer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($recentOrders as $order)
                                    @php
                                        $statusColors = [
                                            'placed'    => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                            'accepted'  => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'ready'     => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                            'completed' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            'declined'  => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        ];
                                        $badgeClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#{{ $order->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $order->customer?->name ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $order->farmer?->stall_name ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white font-medium">
                                            ₱{{ number_format($order->total_amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Recent Farmer Registrations --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Farmer Registrations</h2>
                    <a href="{{ route('admin.farmers.index') }}"
                       class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View all →</a>
                </div>
                @if ($recentFarmers->isEmpty())
                    <div class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">No farmers registered yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stall Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registered</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($recentFarmers as $farmer)
                                    @php
                                        $statusColors = [
                                            'pending'   => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                            'approved'  => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'suspended' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        ];
                                        $badgeClass = $statusColors[$farmer->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $farmer->stall_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $farmer->contact_person }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                                {{ ucfirst($farmer->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                            {{ $farmer->created_at->format('M d, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>