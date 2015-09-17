{{--textbook/buy/product/#--}}


@extends('layouts.textbook')

<title>Stuvi - Book Details - {{ $product->book->title }} </title>

@section('searchbar')
    @include('includes.textbook.searchbar')
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('libs/slick-carousel/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/slick-carousel/slick/slick-theme.css') }}">
@endsection

@section('content')

    <?php $book = $product->book; ?>

    <div class="container">

        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ url('textbook/buy') }}">Home</a></li>
                <li><a href="{{ url('textbook/buy/search?query=' . $query) }}">Search results</a></li>
                <li><a href="{{ url('textbook/buy/' . $product->book->id . '?query=' . $query) }}">{{ $product->book->title }}</a></li>
                <li class="active">Details</li>
            </ol>
        </div>

        <div class="page-header">
            <h1>{{ $book->title }}</h1>
        </div>

        <div class="actions text-right">
            @if(Auth::check())
                @if($product->isDeleted())
                    <a class="btn btn-default disabled" href="#" role="button">Archived</a>
                @elseif($product->sold)
                    <a class="btn btn-default disabled" href="#" role="button">Sold</a>
                @elseif($product->isInCart(Auth::user()->id))
                    <a class="btn btn-primary add-cart-btn disabled" href="#" role="button">Added
                        To Cart</a>
                @elseif(!$product->isSold() && $product->seller == Auth::user())
                    <a href="{{ url('/textbook/sell/product/'.$product->id.'/edit') }}"
                       class="btn btn-primary">Edit</a>

                    <button type="button" class="btn btn-danger" data-toggle="modal"
                            data-target="#delete-product"
                            data-product-id="{{ $product->id }}"
                            data-book-title="{{ $product->book->title }}">Delete</button>
                @else
                    <form method="post" class="add-to-cart">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input class="btn btn-primary add-cart-btn" type="submit" value="Add to cart">
                    </form>
                @endif
            @else
                <span>
                    Please <a data-toggle="modal" href="#login-modal">Login</a> or <a
                            data-toggle="modal" href="#signup-modal">Sign up</a> to buy this textbook.
                </span>
            @endif
        </div>

        <br>

        <div class="row">
            {{-- image slider --}}
            <div class="image-slider">
                @forelse($product->images as $index => $image)
                    <div>
                        <img class="img-responsive" src="{{ $image->getImagePath('large') }}" data-action="zoom">
                    </div>
                @empty
                    <div><h3>No images were provided.</h3></div>
                @endforelse
            </div>
        </div>

        <br>

        <div class="row">
            <!-- product conditions -->
            <table class="table table-default">

                <tbody>
                <tr>
                    <td class="col-xs-6">Price</td>
                    <td class="col-xs-6 price">${{ $product->decimalPrice() }}</td>
                </tr>

                <!-- General Condition -->
                <tr>
                    <td class="col-xs-4">
                        {{ Config::get('product.conditions.general_condition.title') }}
                        <i class="fa fa-question-circle" data-toggle="modal" data-target=".condition-modal"></i>
                    </td>

                    <td class="col-xs-8">{{ Config::get('product.conditions.general_condition')[$product->condition->general_condition] }}</td>
                </tr>
                <!-- Highlights / Notes -->
                <tr>
                    <td>
                        {{ Config::get('product.conditions.highlights_and_notes.title') }}
                        <i class="fa fa-question-circle" data-toggle="modal" data-target=".highlight-modal"></i>
                    </td>
                    <td>{{ Config::get('product.conditions.highlights_and_notes')[$product->condition->highlights_and_notes] }}</td>
                </tr>
                <!-- Damaged Pages -->
                <tr>
                    <td>
                        {{ Config::get('product.conditions.damaged_pages.title') }}
                        <i class="fa fa-question-circle" data-toggle="modal" data-target=".damage-modal"></i>
                    </td>
                    <td>{{ Config::get('product.conditions.damaged_pages')[$product->condition->damaged_pages] }}</td>
                </tr>
                <!-- Broken Binding -->
                <tr>
                    <td>
                        {{ Config::get('product.conditions.broken_binding.title') }}
                        <i class="fa fa-question-circle" data-toggle="modal" data-target=".binding-modal"></i>

                    </td>
                    <td>{{ Config::get('product.conditions.broken_binding')[$product->condition->broken_binding] }}</td>
                </tr>
                <!-- Seller Description -->
                @if($product->condition->hasDescription())
                    <tr>
                        <td>Seller's Description</td>
                        <td>
                            {{ $product->condition->description }}
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        <br>

        <div class="row">
            <div class="panel panel-default">
                <div class="panel-body">
                    {{-- Book info --}}
                    <div class="row">

                        {{-- Image --}}
                        <div class="col-md-3">
                            @if($book->imageSet->small_image)
                                <img class="img-responsive"
                                     src="{{ config('aws.url.stuvi-book-img') . $book->imageSet->small_image }}">
                            @else
                                <img class="img-responsive" src="{{ config('book.default_image_path.small') }}">
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="col-md-9">
                            <table class="table-book-details">
                                <tr>
                                    <th>
                                        @if(count($book->authors) > 1)
                                            Authors
                                        @else
                                            Author
                                        @endif
                                    </th>
                                    <td>
                                        @foreach($book->authors as $index => $author)
                                            @if($index == 0)
                                                <span class="author">{{ $author->full_name }}</span>
                                            @else
                                                <span class="author">, {{ $author->full_name }}</span>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>

                                <tr>
                                    <th>ISBN-10</th>
                                    <td>{{ $book->isbn10 }}</td>
                                </tr>

                                <tr>
                                    <th>ISBN-13</th>
                                    <td>{{ $book->isbn13 }}</td>
                                </tr>

                                <tr>
                                    <th>Number of pages</th>
                                    <td>{{ $book->num_pages }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection

@include('includes.modal.delete-product')
@include('includes.modal.product-conditions')

@section('javascript')
    <script src="{{ asset('libs/slick-carousel/slick/slick.min.js') }}"></script>
    <script src="{{ asset('js/product/show.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('libs/zoom.js/js/zoom.js') }}"></script>
@endsection
