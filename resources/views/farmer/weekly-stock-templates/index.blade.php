<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Weekly Stock Templates
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Add / Update Template Entry --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Add / Update Weekly Stock
            </h3>

            <form method="POST" action="{{ route('farmer.weekly-stock-templates.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Product
                    </label>
                    <select name="product_id" required
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="">— Select a product —</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Weekly Quantity
                    </label>
                    <input type="number" name="weekly_quantity" min="0" value="{{ old('weekly_quantity', 0) }}" required
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    @error('weekly_quantity')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Notes <span class="text-gray-400 text-xs">(optional)</span>
                    </label>
                    <textarea name="notes" rows="3" maxlength="1000"
                              class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                    Save Template
                </button>
            </form>
        </div>

        {{-- Existing Templates --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Current Weekly Stock Templates
            </h3>

            @if($templates->isEmpty())
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    No weekly stock templates defined yet.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left">Product</th>
                                <th class="px-4 py-2 text-right">Weekly Quantity</th>
                                <th class="px-4 py-2 text-left">Notes</th>
                                <th class="px-4 py-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($templates as $template)
                                <tr>
                                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100">
                                        {{ $template->product->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-2 text-right text-gray-900 dark:text-gray-100">
                                        {{ $template->weekly_quantity }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-700 dark:text-gray-200">
                                        {{ $template->notes ?? '—' }}
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        <form method="POST"
                                              action="{{ route('farmer.weekly-stock-templates.destroy', $template) }}"
                                              onsubmit="return confirm('Delete this template entry?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 text-xs bg-red-600 text-white rounded-md hover:bg-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $templates->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>