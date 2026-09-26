<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - MarketLink</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 700px;
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

        .mb-3 {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .btn {
            background: #198754;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .back {
            display: inline-block;
            margin-left: 10px;
            color: #333;
            text-decoration: none;
        }

        .error {
            background: #f8d7da;
            color: #842029;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">

        <h1>Add Product</h1>

        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('farmer.products.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            <div class="mb-3">
                <label for="name">Product Name</label>

                <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="category_id">Category</label>

                <select name="category_id" id="category_id" required>

                    <option value="">-- Select Category --</option>

                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="mb-3">
                <label for="description">Description</label>

                <textarea
                    name="description"
                    id="description"
                >{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="price">Price</label>

                <input
                    type="number"
                    name="price"
                    id="price"
                    step="0.01"
                    min="0"
                    value="{{ old('price') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="unit">Unit</label>

                <input
                    type="text"
                    name="unit"
                    id="unit"
                    placeholder="kg, dozen, piece, etc."
                    value="{{ old('unit') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="stock_quantity">Stock Quantity</label>

                <input
                    type="number"
                    name="stock_quantity"
                    id="stock_quantity"
                    min="0"
                    step="0.01"
                    value="{{ old('stock_quantity') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="image">Product Image</label>

                <input
                    type="file"
                    name="image"
                    id="image"
                    accept=".jpg,.jpeg,.png,.webp"
                >
            </div>

            <div class="mb-3">
                <label>
                    <input
                        type="checkbox"
                        name="is_available"
                        value="1"
                        {{ old('is_available', true) ? 'checked' : '' }}
                    >
                    Product is available
                </label>
            </div>

            <button type="submit" class="btn">
                Add Product
            </button>

            <a
                href="{{ route('farmer.products.index') }}"
                class="back"
            >
                ← Back to Products
            </a>

        </form>

    </div>

</div>

</body>
</html>