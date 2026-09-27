d-800 rounded-lg">
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Product Details Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Product Details</h3>

                <div class="flex gap-6">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-28 h-28 object-cover rounded-lg flex-shrink-0">
                    @else
                        <div class="w-28 h-28 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-xs text-gray-400">No image</span>
                        </div>
                    @endif

                    <div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $product->name }}
                        </h4>

                        <p class="mt-1 text-2xl font-bold text-indigo-600">
                            ₱{{ number_format((float) $product->price, 2) }}
                            <span class="text-base font-normal text-gray-500 dark:text-gray-400">
                                / {{ $product->unit }}
                            </span>
                        </p>

                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Stock available: <span class="font-medium">{{ $product->stock_quantity }}</span>
                        </p>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            Farmer: <span class="font-medium">{{ $product->farmer?->stall_name }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Order Form --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Order Details</h3>

                <form method="POST" action="{{ route('customer.orders.store') }}">
                    @csrf

                    {{-- Hidden product ID --}}
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Quantity --}}
                    <div class="mb-5">
                        <label for="quantity"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Quantity <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            value="{{ old('quantity', 1) }}"
                            min="1"
                            max="{{ $product->stock_quantity }}"
                            required
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 @error('quantity') border-red-500 @enderror"
                        >
                        @error('quantity')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Maximum available: {{ $product->stock_quantity }}
                        </p>
                    </div>

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

                        <a href="{{ route('customer.products.show', $product->id) }}"
                           class="text-gray-600 dark:text-gray-300 hover:underline text-sm">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>

C:\laragon\www\MarketLink(feature/phase5-customer-discovery)
λ