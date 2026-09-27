<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Edit Category</h1>

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Name
                </label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}"
                       class="w-full border-gray-300 rounded shadow-sm"
                       required>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center space-x-2">
                <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded">
                    Update
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="text-sm text-gray-600">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>