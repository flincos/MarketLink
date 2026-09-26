@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Incoming Orders</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="alert alert-info">
            No orders found.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Market</th>
                        <th>Pickup Date</th>
                        <th>Pickup Time</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
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
                                {{ $order->market->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $order->pickup_date?->format('d M Y') }}
                            </td>

                            <td>
                                {{ $order->pickup_time }}
                            </td>

                            <td>
                                {{ number_format($order->total_amount, 2) }}
                            </td>

                            <td>
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </td>

                            <td>
                                <a href="{{ route('farmer.orders.show', $order) }}"
                                   class="btn btn-sm btn-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection