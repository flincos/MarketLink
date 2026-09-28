<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Farmer Dashboard') }}
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

            {{-- Profile status banners --}}
            @if ($farmer->status === 'pending')
                <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-300 dark:border-yellow-600 text-yellow-800 dark:text-yellow-300 px-4 py-3 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Pending Approval:</strong> Your profile is pending admin approval. You cannot manage products or orders until approved.</span>
                </div>
            @elseif ($farmer->status === 'suspended')
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-300 dark:border-red-600 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Account Suspended:</strong> Your account is suspended. Please contact support.</span>
                </div>
            @endif

            {{-- Welcome --}}
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Welcome back, {{ $farmer->stall_name }}!
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Here's an overview of your store activity.</p>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Total Orders --}}
                <a href="{{ route('farmer.orders.index') }}"
                   class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</h3>
                        <span class="text-blue-500 dark:text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</div>
                    <p class="text-xs text-blue-500 dark:text-blue-400 mt-1 group-hover:underline">View all orders →</p>
                </a>

                {{-- Pending Orders --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Orders</h3>
                        <span class="text-orange-500 dark:text-orange-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $pendingOrders }}</div>
                    <p class="text-xs text-gray-400 mt-1">Awaiting acceptance</p>
                </div>

                {{-- Revenue --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue Summary</h3>
                        <span class="text-green-500 dark:text-green-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                        ₱{{ number_format($revenue, 2) }}
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Completed orders</p>
                </div>

                {{-- Total Products --}}
                <a href="{{ route('farmer.products.index') }}"
                   class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products</h3>
                        <span class="text-purple-500 dark:text-purple-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</div>
                    <p class="text-xs text-purple-500 dark:text-purple-400 mt-1 group-hover:underline">Manage products →</p>
                </a>

            </div>

            {{-- Recent Orders --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h2>
                    <a href="{{ route('farmer.orders.index') }}"
                       class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View all</a>
                </div>

                @if ($recentOrders->isEmpty())
                    <div class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                        No orders yet.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pickup Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($recentOrders as $order)
                                    @php
                                        $statusColors = [
                                            'placed'    => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                            'accepted'  => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                            'ready'     => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                            'completed' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                            'declined'  => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        ];
                                        $badgeClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('farmer.orders.show', $order) }}"
                                               class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                                #{{ $order->id }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $order->customer?->name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                            {{ $order->pickup_date?->format('M d, Y') ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-white">
                                            ₱{{ number_format($order->total_amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            {{-- Best-Selling Products --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Best-Selling Products</h2>
                </div>

                @if ($topProducts->isEmpty())
                    <div class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                        No sales data yet.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Product
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Units Sold
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Revenue
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($topProducts as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                            {{ $product->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-200">
                                            {{ number_format($product->total_units_sold, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-white">
                                            ₹{{ number_format($product->total_revenue, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            {{-- Quick Links --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Links</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

                    <a href="{{ route('farmer.products.index') }}"
                       class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex flex-col items-center gap-3 hover:shadow-md transition text-center group">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/40 rounded-full flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Products</span>
                    </a>

                    <a href="{{ route('farmer.markets.index') }}"
                       class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex flex-col items-center gap-3 hover:shadow-md transition text-center group">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Markets</span>
                    </a>

                    <a href="{{ route('farmer.pickup-slots.index') }}"
                       class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex flex-col items-center gap-3 hover:shadow-md transition text-center group">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/40 rounded-full flex items-center justify-center text-green-600 dark:text-green-400 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pickup Slots</span>
                    </a>

                    <a href="{{ route('farmer.reviews.index') }}"
                       class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex flex-col items-center gap-3 hover:shadow-md transition text-center group">
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/40 rounded-full flex items-center justify-center text-yellow-600 dark:text-yellow-400 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Reviews</span>
                    </a>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>