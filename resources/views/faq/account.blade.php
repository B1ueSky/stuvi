@extends('app')

@section('title', 'FAQ')

@section('css')
    <link href="{{ asset('/css/faq.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container faq-container">
        @include('faq/side-nav')
        <div class="col-sm-7 col-sm-offset-1 qa-container">
            <h2>FAQ - Account</h2>
            <hr class="faq-line">
            <div class="question-answer">
                <h3>How do I edit my account information?</h3>
                <p>
                    Please go to the <a href="{{ url('/user/account') }}">account page</a> to edit your account
                    information.
                </p>
            </div>
            <div class="question-answer">
                <h3>How do I change my email settings?</h3>
                <p>
                    You can change all email preferences on the <a href="{{ url('/user/email') }}">email settings
                        page.</a>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
@endsection