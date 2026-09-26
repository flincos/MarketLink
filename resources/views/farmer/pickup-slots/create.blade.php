<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Pickup Slot - MarketLink</title>
</head>

<body style="font-family:Arial; background:#f5f7f6; padding:30px;">

<div style="max-width:600px; margin:auto; background:white; padding:30px; border-radius:10px;">

    <h1>Add Pickup Slot</h1>

    @if($errors->any())
        <div style="color:#dc3545; margin-bottom:20px;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if($markets->count())

        <form action="{{ route('farmer.pickup-slots.store') }}" method="POST">

            @csrf

            <label>Market</label>

            <select name="market_id" required style="width:100%; padding:10px; margin:8px 0 20px;">

                <option value="">-- Select Market --</option>

                @foreach($markets as $market)
                    <option value="{{ $market->id }}">
                        {{ $market->name }}
                    </option>
                @endforeach

            </select>

            <label>Date</label>

            <input
                type="date"
                name="date"
                value="{{ old('date') }}"
                required
                style="width:100%; padding:10px; margin:8px 0 20px;"
            >

            <label>Start Time</label>

            <input
                type="time"
                name="start_time"
                value="{{ old('start_time') }}"
                required
                style="width:100%; padding:10px; margin:8px 0 20px;"
            >

            <label>End Time</label>

            <input
                type="time"
                name="end_time"
                value="{{ old('end_time') }}"
                required
                style="width:100%; padding:10px; margin:8px 0 20px;"
            >

            <label>Capacity</label>

            <input
                type="number"
                name="capacity"
                value="{{ old('capacity') }}"
                min="1"
                required
                style="width:100%; padding:10px; margin:8px 0 20px;"
            >

            <label>
                <input
                    type="checkbox"
                    name="is_available"
                    value="1"
                    checked
                >

                Available for booking
            </label>

            <br><br>

            <button
                type="submit"
                style="background:#198754; color:white; border:none; padding:11px 18px; border-radius:6px;"
            >
                Create Pickup Slot
            </button>

        </form>

    @else

        <p>
            You need to add a market to your profile before creating a pickup slot.
        </p>

        <a href="{{ route('farmer.markets.create') }}">
            Add Market
        </a>

    @endif

</div>

</body>
</html>