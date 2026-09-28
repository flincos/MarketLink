<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Announcements
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if($announcements->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    There are no announcements at this time.
                </p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($announcements as $announcement)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $announcement->title }}
                        </h3>
                        @if($announcement->published_at)
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Published {{ $announcement->published_at->format('F d, Y g:i A') }}
                            </p>
                        @endif
                        @if($announcement->body)
                            <p class="mt-3 text-sm text-gray-700 dark:text-gray-200 whitespace-pre-line">
                                {{ $announcement->body }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>
</x-app-layout>