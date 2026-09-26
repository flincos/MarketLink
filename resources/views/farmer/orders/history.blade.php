@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Order History & Insights</h2>

    <div class="row mb-4">

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Total Orders</h6>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Pending Orders</h6>
                    <h3>{{ $pendingOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h6>Revenue Summary</h6>
                    <h3>{{ number_format($revenue, 2) }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Best-Selling Products</strong>
        </div>

        <div class="card-body">

            @if($bestSellingProducts->isEmpty())

                <p class="mb-0">No completed sales yet.</p>

            @else

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity Sold</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($bestSellingProducts as $product)
                            <tr>
                                <td>{{ $product['product_name'] }}</td>
                                <td>{{ $product['quantity'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @endif

        </div>
    </div>

    <div class="card">

        <div class="card-header">
            <strong>Past Orders</strong>
        </div>

        <div class="card-body">

            @if($orders->isEmpty())

                <p class="mb-0">No past orders yet.</p>

            @else

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($orders as $order)

                                <tr>
                                    <td>{{ $order->id }}</td>

                                    <td>
                                        {{ $order->customer->name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $order->pickup_date?->format('d M Y') }}
                                    </td>

                                    <td>
                                        {{ number_format($order->total_amount, 2) }}
                                    </td>

                                    <td>
                                        {{ ucfirst($order->status) }}
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>

</div>
@endsection