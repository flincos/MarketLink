<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Announcements
        </h2>
    </x-slot>
    
    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if(session('status'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                All Announcements
            </h3>
            <a href="{{ route('admin.announcements.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                + New Announcement
            </a>
        </div>

        @if($announcements->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    No announcements yet.
                </p>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="space-y-4">
                    @foreach($announcements as $announcement)
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-3 last:border-b-0 last:pb-0">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $announcement->title }}
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $announcement->created_at->format('Y-m-d H:i') }}
                                        @if($announcement->is_published && $announcement->published_at)
                                            · Published {{ $announcement->published_at->format('Y-m-d H:i') }}
                                        @else
                                            · Draft
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                       class="px-3 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Edit
                                    </a>
                                    <form method="POST"
                                          action="{{ route('admin.announcements.destroy', $announcement) }}"
                                          onsubmit="return confirm('Delete this announcement?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1 text-xs bg-red-600 text-white rounded-md hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if($announcement->body)
                                <p class="mt-2 text-sm text-gray-700 dark:text-gray-200">
                                    {{ Str::limit($announcement->body, 160) }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $announcements->links() }}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>