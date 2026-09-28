<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Incoming Orders
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($orders->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
                    <p class="text-gray-600 dark:text-gray-300">
                        No orders found.
                    </p>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-left">Order #</th>
                                    <th class="px-4 py-2 text-left">Customer</th>
                                    <th class="px-4 py-2 text-left">Market</th>
                                    <th class="px-4 py-2 text-left">Pickup Date</th>
                                    <th class="px-4 py-2 text-left">Pickup Time</th>
                                    <th class="px-4 py-2 text-right">Total</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="px-4 py-2 text-gray-900 dark:text-gray-100">
                                            {{ $order->id }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                                            {{ $order->customer->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                                            {{ $order->market->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                                            {{ $order->pickup_date?->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                                            {{ $order->pickup_time }}
                                        </td>
                                        <td class="px-4 py-2 text-right text-gray-900 dark:text-gray-100">
                                            ₱{{ number_format((float) $order->total_amount, 2) }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </td>
                                        <td class="px-4 py-2 text-right">
                                            <a href="{{ route('farmer.orders.show', $order) }}"
                                               class="inline-block px-3 py-1 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>