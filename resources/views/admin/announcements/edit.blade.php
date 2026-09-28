<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Announcement
        </h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Title
                    </label>
                    <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    @error('title')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Body <span class="text-gray-400 text-xs">(optional)</span>
                    </label>
                    <textarea name="body" rows="5"
                              class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-sm dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('body', $announcement->body) }}</textarea>
                    @error('body')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="publish_now" name="publish_now" type="checkbox" value="1"
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                           {{ old('publish_now', $announcement->is_published) ? 'checked' : '' }}>
                    <label for="publish_now" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        Published
                    </label>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                        Update
                    </button>
                    <a href="{{ route('admin.announcements.index') }}"
                       class="text-sm text-gray-600 dark:text-gray-300 hover:underline">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>