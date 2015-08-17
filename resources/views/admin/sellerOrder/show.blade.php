@extends('admin')

@section('title', 'Seller Order')

@section('content')

    <h1>Seller Order Detail</h1>

    <table class="table table-hover">
        <tr>
            <th>ID</th>
            <td>{{ $seller_order->id }}</td>
        </tr>
        <tr>
            <th>Product ID</th>
            <td>{{ $seller_order->product_id }}</td>
        </tr>
        <tr>
            <th>BuyerOrder ID</th>
            <td>{{ $seller_order->buyer_order_id }}</td>
        </tr>
        <tr>
            <th>Cancelled</th>
            <td>{{ $seller_order->cancelled }}</td>
        </tr>
        <tr>
            <th>Scheduled Pickup Time</th>
            <td>{{ $seller_order->scheduled_pickup_time }}</td>
        </tr>
        <tr>
            <th>Pickup Time</th>
            <td>{{ $seller_order->pickup_time }}</td>
        </tr>
    </table>

@endsection
