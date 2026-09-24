<x-app-layout>
    <div class="max-w-2xl mx-auto py-12 px-6">
        <h1 class="text-2xl font-bold mb-6">Complete Your Farmer Profile</h1>

        <form method="POST" action="{{ route('farmer.profile.store') }}">
            @csrf

            <div class="mb-4">
                <x-input-label for="stall_name" value="Stall Name" />
                <x-text-input id="stall_name" name="stall_name" type="text" class="mt-1 block w-full" :value="old('stall_name')" required />
                <x-input-error :messages="$errors->get('stall_name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="contact_person" value="Contact Person" />
                <x-text-input id="contact_person" name="contact_person" type="text" class="mt-1 block w-full" :value="old('contact_person')" required />
                <x-input-error :messages="$errors->get('contact_person')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="contact_number" value="Contact Number" />
                <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full" :value="old('contact_number')" required />
                <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="address" value="Address" />
                <textarea id="address" name="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ old('address') }}</textarea>
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <x-primary-button>Submit Profile</x-primary-button>
        </form>
    </div>
</x-app-layout>