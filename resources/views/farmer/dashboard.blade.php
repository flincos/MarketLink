<x-app-layout>
    <div class="p-6">
        @if (session('status'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <h1 class="text-xl font-bold mb-4">Welcome, {{ $profile->stall_name }}!</h1>

        @if($profile->status === 'pending')
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-4">
                Your farmer profile is waiting for approval.
            </div>
        @elseif($profile->status === 'suspended')
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                Your account has been suspended, please contact customer support for appeal.
            </div>
        @endif
    </div>
</x-app-layout>