<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Market - MarketLink</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7f6;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 11px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .btn {
            background: #198754;
            color: white;
            border: none;
            padding: 11px 18px;
            border-radius: 6px;
            cursor: pointer;
        }

        .back {
            display: inline-block;
            margin-top: 15px;
            color: #555;
            text-decoration: none;
        }

        .error {
            color: #dc3545;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">

        <h1>Add Market</h1>

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if($markets->count())

            <form action="{{ route('farmer.markets.store') }}" method="POST">

                @csrf

                <label for="market_id">
                    Select Market
                </label>

                <select name="market_id" id="market_id" required>

                    <option value="">
                        -- Select a Market --
                    </option>

                    @foreach($markets as $market)

                        <option
                            value="{{ $market->id }}"
                            {{ old('market_id') == $market->id ? 'selected' : '' }}
                        >
                            {{ $market->name }}
                            - {{ $market->address }}
                        </option>

                    @endforeach

                </select>

                <button type="submit" class="btn">
                    Add Market
                </button>

            </form>

        @else

            <p>
                No markets are currently available.
            </p>

        @endif

        <a href="{{ route('farmer.markets.index') }}" class="back">
            ← Back to My Markets
        </a>

    </div>

</div>

</body>
</html>