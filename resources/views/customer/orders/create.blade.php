<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Checkout
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Cart summary --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Order Summary
                </h3>

                @php
                    $total = 0;
                @endphp

                <div class="space-y-4">
                    @foreach ($cart as $productId => $quantity)
                        @php
                            $product = $products[$productId] ?? null;
                            if (! $product) continue;
                            $subtotal = $product->price * $quantity;
                            $total += $subtotal;
                        @endphp

                        <div class="flex items-start justify-between border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0">
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $product->name }}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    ₱{{ number_format((float) $product->price, 2) }} / {{ $product->unit }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Quantity: {{ $quantity }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    ₱{{ number_format((float) $subtotal, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex justify-between items-center">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Farmer: <span class="font-medium">{{ $farmer->stall_name }}</span>
                    </p>

                    <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
                        Total: ₱{{ number_format((float) $total, 2) }}
                    </p>
                </div>
            </div>

            {{-- Checkout form --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Pickup Details
                </h3>

                <form method="POST" action="{{ route('customer.orders.store') }}">
                    @csrf

                    {{-- Pickup Slot --}}
                    <div class="mb-5">
                        <label for="pickup_slot_id"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Pickup Slot <span class="text-red-500">*</span>
                        </label>

                        @if ($pickupSlots->isEmpty())
                            <p class="text-sm text-red-600">
                                No pickup slots are currently available for this farmer.
                                Please check back later.
                            </p>
                        @else
                            <select
                                id="pickup_slot_id"
                                name="pickup_slot_id"
                                required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('pickup_slot_id') border-red-500 @enderror"
                            >
                                <option value="">— Select a pickup slot —</option>
                                @foreach ($pickupSlots as $slot)
                                    <option
                                        value="{{ $slot->id }}"
                                        {{ old('pickup_slot_id') == $slot->id ? 'selected' : '' }}
                                    >
                                        {{ $slot->date->format('D, M d, Y') }}
                                        · {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}
                                        – {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                        @if ($slot->market)
                                            · {{ $slot->market->name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('pickup_slot_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    {{-- Notes --}}
                    <div class="mb-6">
                        <label for="notes"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Notes <span class="text-gray-400">(optional)</span>
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            maxlength="500"
                            placeholder="Any special instructions or notes for the farmer..."
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                        >{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        @if ($pickupSlots->isNotEmpty())
                            <button
                                type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Place Order
                            </button>
                        @endif

                        <a href="{{ route('customer.cart.index') }}"
                           class="text-gray-600 dark:text-gray-300 hover:underline text-sm">
                            Back to Cart
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>