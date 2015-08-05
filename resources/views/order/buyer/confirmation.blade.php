@extends('app')

@section('title', 'Order confirmation #'.Session::get('order')->id )

@section('css')
    <link href="{{ asset('/css/order_buyer_confirmation.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="row">
        <div class="container col-xs-12 col-xs-offset-2 col-sm-8 col-sm-offset-2 cart-progress">
            <p><img class="img-responsive cart-line col-sm-offset-3" src="{{asset('/img/CONFIRM.png')}}"
                    alt="Your cart progress"></p>
        </div>
    </div>

    <div class="container confirmation-container">
        <div class="confirmation-details">
            <h1>Thank you for your order</h1>
            <h4>Your order number is: {{ Session::get('order')->id }} <a href="{{ url('order/buyer/'.Session::get('order')->id) }}">View order details</a>.</h4>

            <p>You will receive an email confirmation shortly at <code>{{ Auth::user()->primaryEmail->email_address }}</code>.</p>
            {{--<h5><a href="#">Print Receipt</a></h5>--}}
        </div>
        <div class="next-steps-container">
            <h1>Next Steps</h1>

            <p>You will receive another email in the next few days regarding the delivery time of your books.</p>

            <p>Please feel free to <a href="{{ url('/contact') }}">contact us</a> with any questions or concerns.</p>
        </div>
    </div>
@endsection

@section('javascript')
@endsection