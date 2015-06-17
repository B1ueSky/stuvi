@extends('textbook')


@section('content')
    <head>
        <link href="{{ asset('/css/textbook-show.css') }}" rel="stylesheet">
        <title>{{ $book->title }}</title>
    </head>

    {{--textbook navigation bar--}}
    <div class="tab-filter-container">
        <ul class="tab-filters">
            <li class="filter active">
                <a class="filter-link active" href="{{ url('/textbook/buy') }}">Buy</a>
            </li>
            <li class="filter">
                <a class="filter-link" href="{{ url('/textbook/sell') }}">Sell</a>
            </li>
            <li class="cart">
                <a href="{{ url('/cart') }}" class="cart-link"><i class="fa fa-shopping-cart fa-2x"></i></a>
            </li>
        </ul>
    </div>

    <div class="container">
        <div class="row textbook-row">
            <div class="col-sm-6">
                @if($book->imageSet->large_image)
                    <img id="textbook-img" src="{{ $book->imageSet->large_image }}" alt=""/>
                @endif
            </div>

            <div class="col-sm-6 textbook-info">
                <h1>{{ $book->title }}</h1>

                <div class="authors-container">
                    <span>by </span>
                    @foreach($book->authors as $author)
                        <span id="authors"><button class="btn btn-default author-btn">{{ $author->full_name }}</button></span>
                    @endforeach
                </div>
                <p>ISBN: {{ $book->isbn }}</p>

                <p>Edition: {{ $book->edition }}</p>

                <p>Number of Pages: {{ $book->num_pages }}</p>
                {{-- Author(s) --}}
                {{-- TODO: Make each author name looks like a tag --}}
                {{--<div class="">--}}
                {{--@if(count($book->authors) > 1)--}}
                {{--<span>Authors:</span>--}}
                {{--@foreach($book->authors as $author)--}}
                {{--<span>{{ $author->full_name }}</span>--}}
                {{--@endforeach--}}
                {{--@else--}}
                {{--<span>Author:</span>--}}
                {{--{{ $book->authors[0]->full_name }}--}}
                {{--@endif--}}
                {{--</div>--}}

            </div>
        </div>
        <div class="row table-row">
            <h3>Select one of our available books</h3>
            <table class="table table-responsive textbook-table" style="width:100%" border="1">
                <thead>
                <tr class="active">
                    <th>Price</th>
                    <th>Seller</th>
                    <th>Condition</th>
                    <th>Details</th>
                    <th>Add to Cart</th>
                </tr>
                </thead>
                @foreach($book->products as $product)
                    <tr>
                        <td>
                            <p id="price">${{ $product->price }}</p>
                        </td>
                        <td>
                            <a href="#">Seller's Name</a>
                        </td>
                        <td>
                            {{-- TODO: product condition score --}}
                        </td>
                        <td>
                            <a href="{{ url('textbook/buy/product/'.$product->id) }}" target="_blank">View Details</a>
                        </td>
                        <td class="cart-btn-col">
                            <a class="btn add-cart-btn" href="#" role="button">Add To Cart</a>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div>

@endsection
