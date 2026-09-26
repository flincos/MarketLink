<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Farmer Profile - MarketLink</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f7f5;
            color: #222;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
        }

        input,
        textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        button {
            background: #1f5c3a;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #2d754b;
        }

        .success {
            background: #dff3e6;
            color: #205c38;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .errors {
            background: #f8dddd;
            color: #8b2020;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .errors ul {
            margin-left: 20px;
        }

        @media (max-width: 600px) {
            .row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">

        <h1>Farmer Profile</h1>

        <p class="subtitle">
            Manage your farmer and stall information.
        </p>

        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="errors">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('farmer.profile.update') }}" method="POST">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="stall_name">Stall / Business Name</label>

                <input
                    type="text"
                    id="stall_name"
                    name="stall_name"
                    value="{{ old('stall_name', $farmer->stall_name) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="contact_person">Contact Person</label>

                <input
                    type="text"
                    id="contact_person"
                    name="contact_person"
                    value="{{ old('contact_person', $farmer->contact_person) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="contact_number">Contact Number</label>

                <input
                    type="text"
                    id="contact_number"
                    name="contact_number"
                    value="{{ old('contact_number', $farmer->contact_number) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="address">Address</label>

                <textarea
                    id="address"
                    name="address"
                    required
                >{{ old('address', $farmer->address) }}</textarea>
            </div>

            <div class="row">

                <div class="form-group">
                    <label for="latitude">Latitude</label>

                    <input
                        type="number"
                        step="any"
                        id="latitude"
                        name="latitude"
                        value="{{ old('latitude', $farmer->latitude) }}"
                    >
                </div>

                <div class="form-group">
                    <label for="longitude">Longitude</label>

                    <input
                        type="number"
                        step="any"
                        id="longitude"
                        name="longitude"
                        value="{{ old('longitude', $farmer->longitude) }}"
                    >
                </div>

            </div>
            <div class="form-group">
    <label>Operating Days</label>

    @php
        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday'
        ];

        $selectedDays = old(
            'operating_days',
            $farmer->operating_days ?? []
        );
    @endphp

    @foreach($days as $day)
        <label style="display: block; margin-bottom: 8px;">
            <input
                type="checkbox"
                name="operating_days[]"
                value="{{ $day }}"
                {{ in_array($day, $selectedDays) ? 'checked' : '' }}
            >

            {{ $day }}
        </label>
    @endforeach
</div>
<div class="mb-3">
    <label for="order_cutoff_time" class="form-label">
        Order Cut-off Time
    </label>

    <input
        type="time"
        name="order_cutoff_time"
        id="order_cutoff_time"
        class="form-control"
        value="{{ old('order_cutoff_time', $farmer->order_cutoff_time) }}"
    >

    @error('order_cutoff_time')
        <div class="text-danger">
            {{ $message }}
        </div>
    @enderror
</div>
            

            <div class="form-group">
                <label for="description">Description</label>

                <textarea
                    id="description"
                    name="description"
                >{{ old('description', $farmer->description) }}</textarea>
            </div>

            <button type="submit">
                Save Profile
            </button>

        </form>

    </div>

</div>

</body>
</html>