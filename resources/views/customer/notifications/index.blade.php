<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Notifications
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Mark All as Read --}}
            @if ($notifications->whereNull('read_at')->count() > 0)
                <form
                    method="POST"
                    action="{{ route('customer.notifications.readAll') }}"
                    class="mb-6">

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Mark All as Read
                    </button>
                </form>
            @endif

            {{-- Notifications List --}}
            @forelse ($notifications as $notification)
                <div class="mb-4 rounded-lg shadow p-5
                    {{ $notification->read_at
                        ? 'bg-white dark:bg-gray-800'
                        : 'bg-indigo-50 dark:bg-gray-700 border-l-4 border-indigo-600' }}">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $notification->data['product_name'] ?? 'Notification' }}
                            </h3>

                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ $notification->data['message'] ?? 'You have a new notification.' }}
                            </p>

                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>

                            @if (isset($notification->data['product_id']))
                                <a
                                    href="{{ route('customer.products.index') }}"
                                    class="inline-block mt-3 text-indigo-600 hover:underline">
                                    Browse Products
                                </a>
                            @endif
                        </div>

                        @if (! $notification->read_at)
                            <form
                                method="POST"
                                action="{{ route('customer.notifications.read', $notification->id) }}">

                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="px-4 py-2 border rounded-md text-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                                    Mark as Read
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                Read
                            </span>
                        @endif

                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                    <p class="text-gray-600 dark:text-gray-300">
                        You don't have any notifications yet.
                    </p>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>

        </div>
    </div>
</x-app-layout>