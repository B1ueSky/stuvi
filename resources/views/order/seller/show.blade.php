{{-- Your orders page --}}

@extends('layouts.textbook')

@section('title', 'Order details - Order #'.$seller_order->id)

@section('content')
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ url('order/seller') }}">Your sold books</a></li>
                <li class="active">Order #{{ $seller_order->id }}</li>
            </ol>
        </div>

        <div class="page-header">
            <h1>Order Details</h1>
        </div>

        @if ($seller_order->isDelivered()
        && $seller_order->product->payout_method == 'paypal'
        && empty($seller_order->payout_item_id)
        && empty($seller_order->seller()->profile->paypal))
            <div class="alert alert-warning" role="alert">
                Please fill in your Paypal account in <a href="{{ url('user/profile') }}"><strong>profile</strong></a> to transfer your balance.
            </div>
        @endif


        <div class="panel panel-default">
            <div class="panel-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-5">
                            <span>
                                @if($seller_order->buyer_order_id)
                                    Sold on
                                @else
                                    Trade-in approved at
                                @endif
                                    {{ $seller_order->created_at }}
                            </span>
                        </div>
                        <div class="col-sm-5">
                            @if($seller_order->cancelled)
                                <span>Cancelled at {{ $seller_order->cancelled_time }}</span>
                            @endif
                        </div>
                        <div class="col-sm-2">
                            <span>Order #{{ $seller_order->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- item --}}
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="container-fluid">
                    {{-- order status --}}
                    <div class="row">
                        <?php $order_status = $seller_order->getOrderStatus(); ?>

                        <h3>{{ $order_status['status'] }}</h3>
                        <span>{{ $order_status['detail'] }}</span>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-9">
                            <!-- product list -->
                            <div class="row">
                                <?php $product = $seller_order->product;?>

                                @include('includes.textbook.product-details')
                            </div>
                        </div>

                        {{-- action buttons --}}
                        <div class="col-md-3">
                            @if ($seller_order->isPickupSchedulable())
                                <a class="btn btn-primary btn-block" href="{{ url('order/seller/' . $seller_order->id . '/schedulePickup') }}">Update Pickup Details</a>
                            @endif

                            {{-- cancel order --}}
                            @if ($seller_order->isCancellable())
                                @if($seller_order->buyer_order_id)
                                    <a class="btn btn-danger btn-block cancel-order-btn" href="/order/seller/cancel/{{ $seller_order->id }}" role="button">Cancel Order</a>
                                @else
                                    <form action="{{ url('order/seller/cancelTradeIn') }}" method="post" class="margin-top-5">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="seller_order_id" value="{{ $seller_order->id }}">

                                        <button type="submit" class="btn btn-default btn-block">Not interested</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- balance --}}
        @if ($seller_order->isDelivered() && $seller_order->product->payout_method == 'paypal')
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="container-fluid">
                    <div class="row">
                        <h3>Balance</h3>
                    </div>

                    <br>

                    <div class="row">
                    @if ($seller_order->payout_item_id)
                        <p>Payout Item ID: {{ $seller_order->payout_item_id}}</p>
                    @else
                        <form action="{{url('/order/seller/'.$seller_order->seller()->id.'/payout')}}" method="POST" class="form-horizontal">
                            {!! csrf_field() !!}
                            <input type="hidden" name="seller_order_id" value="{{ $seller_order->id }}">

                            <div class="form-group">
                                <div class=" col-sm-6">
                                    <button id="save-info-btn" type="submit" class="btn btn-primary">Transfer balance to my Paypal account
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
