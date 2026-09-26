<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Markets - MarketLink</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7f6;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        h1 {
            margin: 0;
            color: #1f2937;
        }

        .btn {
            background: #198754;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
        }

        .success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .card h2 {
            margin-top: 0;
            color: #198754;
        }

        .card p {
            color: #555;
            line-height: 1.5;
        }

        .remove {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .empty {
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>My Markets</h1>

        <a href="{{ route('farmer.markets.create') }}" class="btn">
            + Add Market
        </a>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if($markets->count())

        <div class="grid">

            @foreach($markets as $market)

                <div class="card">

                    <h2>{{ $market->name }}</h2>

                    <p>
                        <strong>Address:</strong><br>
                        {{ $market->address }}
                    </p>

                    @if($market->description)
                        <p>
                            <strong>Description:</strong><br>
                            {{ $market->description }}
                        </p>
                    @endif

                    @if($market->latitude && $market->longitude)
                        <p>
                            <strong>Location:</strong><br>
                            {{ $market->latitude }},
                            {{ $market->longitude }}
                        </p>
                    @endif

                    <form
                        action="{{ route('farmer.markets.destroy', $market) }}"
                        method="POST"
                        onsubmit="return confirm('Remove this market from your profile?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="remove">
                            Remove Market
                        </button>
                    </form>

                </div>

            @endforeach

        </div>

    @else

        <div class="empty">
            <h2>No Markets Added</h2>

            <p>
                You have not associated your farmer profile with any markets yet.
            </p>

            <a href="{{ route('farmer.markets.create') }}" class="btn">
                Add Your First Market
            </a>
        </div>

    @endif

</div>

</body>
</html>