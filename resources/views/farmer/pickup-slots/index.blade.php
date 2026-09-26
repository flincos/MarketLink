<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pickup Slots - MarketLink</title>
</head>

<body style="font-family: Arial; background:#f5f7f6; padding:30px;">

<div style="max-width:1100px; margin:auto;">

    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Pickup Slots</h1>

        <a href="{{ route('farmer.pickup-slots.create') }}"
           style="background:#198754; color:white; padding:10px 16px; text-decoration:none; border-radius:6px;">
            + Add Pickup Slot
        </a>
    </div>

    @if(session('success'))
        <div style="background:#d1e7dd; padding:12px; margin:20px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if($slots->count())

        @foreach($slots as $slot)

            <div style="background:white; padding:20px; margin:15px 0; border-radius:10px;">

                <h2>{{ $slot->market->name }}</h2>

                <p>
                    <strong>Date:</strong>
                    {{ $slot->date->format('d M Y') }}
                </p>

                <p>
                    <strong>Time:</strong>
                    {{ $slot->start_time }} - {{ $slot->end_time }}
                </p>

                <p>
                    <strong>Capacity:</strong>
                    {{ $slot->capacity }}
                </p>

                <p>
                    <strong>Status:</strong>
                    {{ $slot->is_available ? 'Available' : 'Unavailable' }}
                </p>

                <a href="{{ route('farmer.pickup-slots.edit', $slot) }}"
                   style="background:#0d6efd; color:white; padding:8px 12px; text-decoration:none; border-radius:5px;">
                    Edit
                </a>

                <form
                    action="{{ route('farmer.pickup-slots.destroy', $slot) }}"
                    method="POST"
                    style="display:inline;"
                    onsubmit="return confirm('Delete this pickup slot?')"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            style="background:#dc3545; color:white; border:none; padding:8px 12px; border-radius:5px;">
                        Delete
                    </button>
                </form>

            </div>

        @endforeach

    @else

        <div style="background:white; padding:30px; margin-top:20px; text-align:center;">
            <h2>No Pickup Slots</h2>
            <p>You haven't created any pickup slots yet.</p>
        </div>

    @endif

</div>

</body>
</html>