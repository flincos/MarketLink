<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Create Market</h1>

        <form method="POST" action="{{ route('admin.markets.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border-gray-300 rounded shadow-sm" required>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" rows="3"
                          class="w-full border-gray-300 rounded shadow-sm"
                          required>{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border-gray-300 rounded shadow-sm">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Operating Days</label>
                @php
                    $weekdayOptions = \App\Models\Market::weekdayOptions();
                    $selectedDays = old('operating_days', isset($market) && $market->operating_days ? $market->operating_days : []);
                @endphp
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($weekdayOptions as $code => $label)
                        <div class="form-check me-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="operating_days[]"
                                id="operating_day_{{ $code }}"
                                value="{{ $code }}"
                                {{ in_array($code, $selectedDays ?? []) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="operating_day_{{ $code }}">
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('operating_days')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="opening_time" class="form-label">Opening Time</label>
                    <input
                        type="time"
                        name="opening_time"
                        id="opening_time"
                        class="form-control @error('opening_time') is-invalid @enderror"
                        value="{{ old('opening_time', isset($market) && $market->opening_time ? $market->opening_time->format('H:i') : '') }}"
                    >
                    @error('opening_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="closing_time" class="form-label">Closing Time</label>
                    <input
                        type="time"
                        name="closing_time"
                        id="closing_time"
                        class="form-control @error('closing_time') is-invalid @enderror"
                        value="{{ old('closing_time', isset($market) && $market->closing_time ? $market->closing_time->format('H:i') : '') }}"
                    >
                    @error('closing_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>                
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <input type="text" name="latitude" value="{{ old('latitude') }}"
                           class="w-full border-gray-300 rounded shadow-sm">
                    @error('latitude')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <input type="text" name="longitude" value="{{ old('longitude') }}"
                           class="w-full border-gray-300 rounded shadow-sm">
                    @error('longitude')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <button class="px-4 py-2 bg-green-600 text-white text-sm rounded">
                    Save
                </button>
                <a href="{{ route('admin.markets.index') }}" class="text-sm text-gray-600">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>