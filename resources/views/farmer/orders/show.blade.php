@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order #{{ $order->id }}</h2>

        <a href="{{ route('farmer.orders.index') }}" class="btn btn-secondary">
            Back to Orders
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <strong>Order Information</strong>
        </div>

        <div class="card-body">
            <p>
                <strong>Customer:</strong>
                {{ $order->customer->name ?? 'N/A' }}
            </p>

            <p>
                <strong>Market:</strong>
                {{ $order->market->name ?? 'N/A' }}
            </p>

            <p>
                <strong>Pickup Date:</strong>
                {{ $order->pickup_date?->format('d M Y') }}
            </p>

            <p>
                <strong>Pickup Time:</strong>
                {{ $order->pickup_time }}
            </p>

            <p>
                <strong>Total Amount:</strong>
                {{ number_format($order->total_amount, 2) }}
            </p>

            <p>
                <strong>Status:</strong>
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
            </p>

            @if($order->notes)
                <p>
                    <strong>Notes:</strong>
                    {{ $order->notes }}
                </p>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Ordered Products</strong>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Order Status</strong>
        </div>

        <div class="card-body">

            @if($order->status === 'placed')
                <form method="POST"
                      action="{{ route('farmer.orders.update-status', $order) }}"
                      class="d-inline">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="accepted">

                    <button type="submit" class="btn btn-success">
                        Accept Order
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('farmer.orders.update-status', $order) }}"
                      class="d-inline">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="declined">

                    <button type="submit" class="btn btn-danger">
                        Decline Order
                    </button>
                </form>

            @elseif($order->status === 'accepted')

                <form method="POST"
                      action="{{ route('farmer.orders.update-status', $order) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="ready">

                    <button type="submit" class="btn btn-primary">
                        Mark Ready for Pickup
                    </button>
                </form>

            @elseif($order->status === 'ready')

                <form method="POST"
                      action="{{ route('farmer.orders.update-status', $order) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status" value="completed">

                    <button type="submit" class="btn btn-success">
                        Mark Completed
                    </button>
                </form>

            @else
                <p class="mb-0">
                    No further action available for this order.
                </p>
            @endif

        </div>
    </div>

</div>
@endsection