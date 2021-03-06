{{--textbook/buy/product/#--}}


@extends('layouts.textbook')

<title>Stuvi - Book Details - {{ $product->book->title }} </title>

@section('content')

    <?php $book = $product->book; ?>

    <div class="container">

        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li>
                    <a href="{{ url('textbook/search?query=' . $query) }}">Search results</a>
                </li>
                <li>
                    <a href="{{ url('textbook/buy/' . $product->book->id . '?query=' . $query) }}">{{ $product->book->title }}</a>
                </li>
                <li class="active">Details</li>
            </ol>
        </div>

        {{--@if(!$product->verified && !$product->is_rejected && !$product->price)--}}
            {{--<div class="alert alert-info fade in" role="alert">--}}
                {{--This book is waiting for the approval by Stuvi. We'll email you as soon as it is approved.--}}
            {{--</div>--}}
        {{--@endif--}}

        {{--@if($product->sold)--}}
            {{--<div class="alert alert-danger fade in" role="alert">--}}
                {{--This book has been sold.--}}
            {{--</div>--}}
        {{--@endif--}}

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>{{ $book->title }}</h1>
                </div>

                <div class="container-flex">
                    @forelse($product->images as $index => $image)
                        <div class="margin-5">
                            <img class="img-rounded full-width img-flex" src="{{ $image->getImagePath('large') }}" data-action="zoom">
                        </div>
                    @empty
                        <h3>No images were provided.</h3>
                    @endforelse
                </div>

                <hr>

                <div>
                    @if(Auth::check())
                        @if($product->isInCart(Auth::user()->id))
                            <a class="btn btn-default add-cart-btn disabled" href="#" role="button">
                                Added to cart
                            </a>
                        @elseif(!$product->sold)
                            @if($product->seller_id == Auth::id())
                                <a href="{{ url('/textbook/sell/product/'.$product->id.'/edit') }}" class="btn btn-default">
                                    <span class="glyphicon glyphicon-edit"></span> Edit
                                </a>

                                <button type="button" class="btn btn-default" data-toggle="modal"
                                        data-target="#delete-product"
                                        data-product-id="{{ $product->id }}"
                                        data-book-title="{{ $product->book->title }}">
                                    <span class="glyphicon glyphicon-remove"></span> Delete
                                </button>

                                @if(!$product->accept_trade_in)
                                    <form action="{{ url('textbook/sell/product/joinTradeIn') }}" method="post" class="form-button">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-warning" id="book-trade-in-popover">
                                            <span class="glyphicon glyphicon-usd"></span> Trade in my book
                                        </button>
                                    </form>
                                @else
                                    <a href="#" class="btn btn-default disabled">
                                        <span class="glyphicon glyphicon-ok"></span>
                                        Trade-in Program
                                    </a>
                                @endif
                            @else
                                <form method="post" class="add-to-cart form-button" action="{{ url('cart/add/' . $product->id) }}">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-warning add-cart-btn">
                                        <span class="glyphicon glyphicon-shopping-cart"></span> Add to cart
                                    </button>
                                </form>
                            @endif
                        @else
                            <a class="btn btn-default disabled" href="#" role="button">
                                Sold
                            </a>
                        @endif
                    @else
                        @if(!$product->sold)
                            <span><a data-toggle="modal" href="#login-modal">Login</a> or <a
                                        data-toggle="modal" href="#signup-modal">Sign up</a> to buy this textbook.
                        </span>
                        @endif
                    @endif
                </div>

                <br>

                <div>
                    <table class="table">

                        <tbody>
                        {{-- Price --}}
                        <tr>
                            <th class="col-sm-6 col-xs-7">Price</th>
                            <td class="col-sm-6 col-xs-5 price">
                                ${{ $product->price }}
                            </td>
                        </tr>

                        {{-- Trade-in price --}}
                        @if($product->seller_id == Auth::id() && $product->accept_trade_in && $product->trade_in_price > 0)
                            <tr>
                                <th>Trade-in price</th>
                                <td class="price">${{ $product->trade_in_price }}</td>
                            </tr>
                        @endif

                        {{-- Availability --}}
                        @if(!$product->sold)
                            <tr>
                                <th>Availability</th>
                                <td>{{ $product->availability() }}</td>
                            </tr>
                            @endif

                                    <!-- General Condition -->
                            <tr>
                                <th>
                                    General condition
                                    <span class="glyphicon glyphicon-question-sign" id="book-general-condition-popover"></span>
                                </th>

                                <td>{{ config('product.conditions.general_condition')[$product->condition->general_condition] }}</td>
                            </tr>
                            <!-- Highlights / Notes -->
                            <tr>
                                <th>
                                    Highlights/Notes
                                    <span class="glyphicon glyphicon-question-sign" id="book-highlights-notes-popover"></span>
                                </th>
                                <td>{{ config('product.conditions.highlights_and_notes')[$product->condition->highlights_and_notes] }}</td>
                            </tr>
                            <!-- Damaged Pages -->
                            <tr>
                                <th>
                                    Damaged pages
                                    <span class="glyphicon glyphicon-question-sign" id="book-damaged-pages-popover"></span>
                                </th>
                                <td>{{ config('product.conditions.damaged_pages')[$product->condition->damaged_pages] }}</td>
                            </tr>
                            <!-- Broken Binding -->
                            <tr>
                                <th>
                                    Broken binding
                                    <span class="glyphicon glyphicon-question-sign" id="book-broken-binding-popover"></span>
                                </th>
                                <td>{{ config('product.conditions.broken_binding')[$product->condition->broken_binding] }}</td>
                            </tr>
                            <!-- Seller Description -->
                            @if($product->condition->hasDescription())
                                <tr>
                                    <th>Additional description</th>
                                    <td>
                                        {{ $product->condition->description }}
                                    </td>
                                </tr>
                            @endif

                            {{-- Posted time--}}
                            <tr>
                                <th>Posted time</th>
                                <td>
                                    <span class="product-posted-time">{{ $product->created_at }}</span>
                                </td>
                            </tr>

                            {{--@if($product->seller_id == Auth::id())--}}
                            {{--<tr>--}}
                            {{--<th>Views</th>--}}
                            {{--<td>--}}
                            {{--{{ $product->views() }}--}}
                            {{--</td>--}}
                            {{--</tr>--}}
                            {{--@endif--}}
                        </tbody>
                    </table>

                </div>

                <br>
            </div>
        </div>



    </div>


@endsection

@include('includes.modal.delete-product')
