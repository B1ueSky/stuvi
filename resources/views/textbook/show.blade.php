{{-- /textbook/buy/# --}}

@extends('layouts.textbook')

@section('title',$book->title)

@section('css')
    <link rel="stylesheet" href="{{ asset('libs/zoom.js/css/zoom.css') }}">
    <script src="{{ asset('libs/lazyload/build/lazyload.min.js') }}"></script>
@endsection

@section('searchbar')
    @include('includes.textbook.searchbar')
@endsection

@section('content')

    <div class="container">

        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ url('textbook/buy') }}">Home</a></li>

                <li>
                    @if(Auth::check())
                        <a href="{{ url('textbook/buy/search?query=' . $query) }}">Search results</a>
                    @else
                        <a href="{{ url('textbook/buy/search?query=' . $query . '&university_id=' . $university_id) }}">Search results</a>
                    @endif
                </li>

                <li class="active">{{ $book->title }}</li>
            </ol>
        </div>

        <div class="page-header">
            <h1>{{ $book->title }}</h1>
        </div>

        @include('includes.textbook.book-details')

        <br/>

        <div class="row">
            <div class="col-xs-12">
                @if(Auth::guest())
                    <p>
                        Please <a data-toggle="modal" href="#login-modal">Login</a> or <a data-toggle="modal"
                                                                                          href="#signup-modal">Sign
                            up</a> to buy or sell a textbook.
                    </p>
                @else
                    <p>
                        <a href="{{ url('textbook/sell/product/'.$book->id.'/create') }}">Have one to sell?</a>
                    </p>
                @endif
            </div>
        </div>

        <br>

        {{-- Product list --}}
        <div class="row">
            @if(count($book->availableProducts()) > 0)

                <table class="table">
                    <thead>
                    <tr class="active">
                        <th class="col-xs-2">Price</th>
                        <th class="col-xs-2">Condition</th>
                        <th class="col-xs-6 hidden-xs">Images</th>
                        <th class="col-xs-2"></th>
                    </tr>
                    </thead>
                    @foreach($book->availableProducts() as $product)
                        <tr>
                            <td class="price">
                                ${{ $product->decimalPrice() }}
                            </td>
                            <td>
                                {{ $product->general_condition() }}
                            </td>
                            <td class="container-flex hidden-xs">
                                @foreach($product->images as $image)
                                    <div>
                                        <img class="img-rounded img-small margin-5 full-width"
                                             src="{{ config('image.lazyload') }}"
                                             data-action="zoom"
                                             data-src="{{ $image->getImagePath('large') }}"
                                             onload="lzld(this)">
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                @if(Auth::check())
                                    <a href="{{ url('textbook/buy/product/'.$product->id.'?query='.$query) }}">View details</a>
                                @else
                                    <a href="{{ url('textbook/buy/product/'.$product->id.'?query='.$query.'&university_id='.$university_id) }}">View details</a>
                                @endif
                            </td>

                        </tr>
                    @endforeach

                </table>
            @endif
        </div>


    </div>

@endsection

@section('javascript')
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('libs/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/autocomplete.js')}} "></script>
    <script src="{{ asset('libs/zoom.js/js/zoom.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
@endsection