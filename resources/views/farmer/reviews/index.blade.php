<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Customer Reviews
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($reviews->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-0">
                        No customer reviews yet.
                    </p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                {{ $review->product->name ?? 'Product' }}
                            </h3>

                            <p class="mb-1 text-sm text-gray-700 dark:text-gray-200">
                                <strong>Customer:</strong>
                                {{ $review->user->name ?? 'N/A' }}
                            </p>

                            <p class="mb-1 text-sm text-gray-700 dark:text-gray-200">
                                <strong>Rating:</strong>
                                {{ $review->rating }}/5
                            </p>

                            @if($review->comment)
                                <p class="mb-3 text-sm text-gray-700 dark:text-gray-200">
                                    <strong>Review:</strong>
                                    {{ $review->comment }}
                                </p>
                            @endif

                            @if($review->farmer_response)
                                <div class="mt-3 p-3 rounded bg-gray-100 dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-100">
                                    <strong>Your Response:</strong>
                                    {{ $review->farmer_response }}
                                </div>
                            @else
                                <form method="POST"
                                      action="{{ route('farmer.reviews.respond', $review) }}"
                                      class="mt-3 space-y-2">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <label for="farmer_response_{{ $review->id }}"
                                               class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Respond to Customer
                                        </label>

                                        <textarea
                                            name="farmer_response"
                                            id="farmer_response_{{ $review->id }}"
                                            rows="3"
                                            maxlength="1000"
                                            required
                                            class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                        ></textarea>
                                    </div>

                                    <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                                        Submit Response
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>