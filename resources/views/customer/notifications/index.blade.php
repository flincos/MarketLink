<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Notifications</h1>

        <ul class="space-y-3">
            @forelse($notifications as $notification)
                <li class="p-4 bg-white shadow rounded border border-gray-200">
                    <div class="text-sm text-gray-500 mb-1">
                        {{ $notification->created_at->format('Y-m-d H:i') }}
                    </div>
                    <div class="font-medium">
                        {{ $notification->data['message'] ?? 'Notification' }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        Type: {{ $notification->data['type'] ?? class_basename($notification->type) }}
                    </div>
                </li>
            @empty
                <li class="text-gray-500 text-sm">
                    No notifications yet.
                </li>
            @endforelse
        </ul>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</x-app-layout>