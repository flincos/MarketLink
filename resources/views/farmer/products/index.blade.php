<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Products - MarketLink</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
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
        }

        .btn {
            background: #198754;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
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

        .product-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .no-image {
            height: 180px;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-bottom: 15px;
            color: #6c757d;
        }

        .available {
            color: #198754;
            font-weight: bold;
        }

        .unavailable {
            color: #dc3545;
            font-weight: bold;
        }

        .actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        .edit {
            background: #0d6efd;
        }

        .delete {
            background: #dc3545;
        }

        .empty {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>My Products</h1>

        <a href="{{ route('farmer.products.create') }}" class="btn">
            + Add Product
        </a>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if($products->count())

        <div class="grid">

            @foreach($products as $product)

                <div class="card">

                    @if($product->image)
                        <img
                            src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}"
                            class="product-image"
                        >
                    @else
                        <div class="no-image">
                            No Image
                        </div>
                    @endif

                    <h2>{{ $product->name }}</h2>

                    <p>
                        <strong>Category:</strong>
                        {{ $product->category->name ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Price:</strong>
                        {{ number_format($product->price, 2) }}
                        / {{ $product->unit }}
                    </p>

                    <p>
                        <strong>Stock:</strong>
                        {{ $product->stock_quantity }}
                        {{ $product->unit }}
                    </p>

                    @if($product->description)
                        <p>
                            <strong>Description:</strong><br>
                            {{ $product->description }}
                        </p>
                    @endif

                    @if($product->is_available)
                        <p class="available">Available</p>
                    @else
                        <p class="unavailable">Unavailable</p>
                    @endif

                    <div class="actions">

                        <a
                            href="{{ route('farmer.products.edit', $product) }}"
                            class="btn edit"
                        >
                            Edit
                        </a>

                        <form
                            action="{{ route('farmer.products.destroy', $product) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this product?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn delete">
                                Delete
                            </button>
                        </form>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="empty">
            <h2>No Products Yet</h2>

            <p>
                You have not added any products to your farmer profile.
            </p>

            <a href="{{ route('farmer.products.create') }}" class="btn">
                Add Your First Product
            </a>
        </div>

    @endif

</div>

</body>
</html>