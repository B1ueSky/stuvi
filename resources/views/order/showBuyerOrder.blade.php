@extends('app')

@section('content')
    <div class="container" xmlns="http://www.w3.org/1999/html">
        @if (Session::has('message'))
            <div class="flash-message">{{ Session::get('message') }}</div>
        @endif
    </div>
    <div class="container">
        <h1>Order #{{ $buyer_order->id }} @if ($buyer_order->cancelled) (CANCELLED) @endif</h1>
        @if (!$buyer_order->cancelled)
            <p><a href="/order/cancel/{{ $buyer_order->id }}">Cancel Order</a></p>
        @endif
        @if ($buyer_order->deliver_time)
            <p><a href="">Return or replace items</a></p>
        @endif

        <p>{{ $buyer_order->created_at }}</p>
        <?php $shipping_address = $buyer_order->shipping_address ?>
        <p>To {{ $shipping_address->addressee }} @ {{ $shipping_address->address_line1 }}  {{ $shipping_address->city }}, {{ $shipping_address->state_a2 }}  {{ $shipping_address->zip }}</p>
        <p>Total: ${{ $buyer_order->buyer_payment->stripe_amount/100 }}</p>

        <div class="container">

            @foreach ($buyer_order->products() as $product)
                <div class="row">
                    {{ var_dump($product) }}
                </div>
            @endforeach
        </div>

    </div>

@endsection