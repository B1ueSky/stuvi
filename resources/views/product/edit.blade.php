{{--/textbook/sell/product/edit/#--}}

@extends('layouts.textbook')

@section('title', 'Edit Product Info')

@section('content')

    <div class="container">

        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                <div class="row">
                    <ol class="breadcrumb">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('user/bookshelf') }}">Your bookshelf</a></li>
                        <li class="active">Edit</li>
                    </ol>
                </div>

                <div class="page-header">
                    <h2>Edit your book</h2>
                </div>

                <div class="row">
                    @if(Auth::check())
                        <form id="form-edit-product" class="dropzone form-product" action="{{ url('/textbook/sell/product/update') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="product_id" value="{{ $product->id }}"/>
                            <input type="hidden" name="general_condition_selected" value="{{ $product->condition->general_condition }}">
                            <input type="hidden" name="highlights_and_notes_selected" value="{{ $product->condition->highlights_and_notes }}">
                            <input type="hidden" name="damaged_pages_selected" value="{{ $product->condition->damaged_pages }}">
                            <input type="hidden" name="broken_binding_selected" value="{{ $product->condition->broken_binding }}">
                            <input type="hidden" name="available_at_selected" value="{{ $product->available_at }}">

                            <section class="details">
                                <div class="panel panel-presentation">

                                    <div class="panel-body">

                                        {{--General Condition--}}
                                        <div class="form-group">
                                            <label>General condition</label>
                                            <span class="glyphicon glyphicon-question-sign" id="book-general-condition-popover"></span>

                                            <div class="radio-button-group">
                                                <label class="radio-inline">
                                                    <input type="radio" name="general_condition" value="0">
                                                    <span>Like new</span>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="general_condition" value="1">
                                                    <span>Good</span>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="general_condition" value="2">
                                                    <span>Acceptable</span>
                                                </label>
                                            </div>
                                        </div>

                                        {{--Highlights/Notes--}}
                                        <div class="form-group">
                                            <label>Highlights/Notes</label>
                                            <span class="glyphicon glyphicon-question-sign" id="book-highlights-notes-popover"></span>


                                            <div class="radio-button-group">
                                                <label class="radio-inline">
                                                    <input type="radio" name="highlights_and_notes" value="0">
                                                    <span>0 ~ 5 pages</span>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="highlights_and_notes" value="1">
                                                    <span>6 ~ 15 pages</span>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="highlights_and_notes" value="2">
                                                    <span>> 15 pages</span>
                                                </label>
                                            </div>
                                        </div>

                                        {{--Damaged Pages--}}
                                        <div class="form-group">
                                            <label>Damaged pages</label>
                                            <span class="glyphicon glyphicon-question-sign" id="book-damaged-pages-popover"></span>

                                            <div class="radio-button-group">
                                                <label class="radio-inline">
                                                    <input type="radio" name="damaged_pages" value="0">
                                                    <span>0</span>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="damaged_pages" value="1">
                                                    <span>1 ~ 3 pages</span>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="damaged_pages" value="2">
                                                    <span>> 3 pages</span>
                                                </label>
                                            </div>
                                        </div>

                                        {{--Broken Binding--}}
                                        <div class="form-group">
                                            <label>Broken book binding</label>
                                            <span class="glyphicon glyphicon-question-sign" id="book-broken-binding-popover"></span>

                                            <div class="radio-button-group">
                                                <label class="radio-inline">
                                                    <input type="radio" name="broken_binding" value="0">
                                                    <span>No</span>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="broken_binding" value="1">
                                                    <span>Yes</span>
                                                </label>
                                            </div>
                                        </div>

                                        {{--Description--}}
                                        <div class="form-group">
                                            <label>Additional description <small>(optional)</small></label>
                                                <textarea name="description" class="form-control" rows="5"
                                                          placeholder="More description on your book conditions.">{{ $product->condition->description }}</textarea>
                                        </div>

                                        {{--Upload Images using Dropzone--}}
                                        <div class="form-group">
                                            {{--<label>Upload Textbook Image</label>--}}

                                            <div id="dropzone-img-preview" class="dropzone-previews dz-clickable">
                                                <div class="dz-message">
                                                    <h4>
                                                        <span class="glyphicon glyphicon-picture"></span>
                                                        Drop or click here to upload textbook images.
                                                    </h4>
                                                    <br>
                                                    <p class="text-muted">A front cover image is required.</p>
                                                    <p class="text-muted">You can upload a maximum of
                                                        three images, at most 3MB per image.</p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Availability --}}
                                        <div class="from-group">
                                            <label>When is it available?</label>

                                            <div class="radio-button-group">
                                                <label class="radio-inline">
                                                    <input type="radio" name="available_at" id="available_now" value="" checked>
                                                    <span>Now</span>
                                                </label>

                                                <label class="radio-inline">
                                                    <input type="radio" name="available_at" id="available_future" value="">
                                                    <span>In the future</span>
                                                </label>
                                            </div>

                                            <div id="datetimepicker-available-date-update" class="hidden"></div>

                                            <br>
                                        </div>

                                        {{--your price--}}
                                        <div class="form-group" id="sale-price">
                                            <label>Sale Price</label>

                                            <div class="input-group">
                                                <div class="input-group-addon">$</div>
                                                <input type="number" step="0.01" min="0.00" name="price"
                                                       class="form-control" placeholder="Set a sale price for your book" value="{{ $product->decimalPrice() }}">
                                            </div>
                                        </div>

                                        {{-- Receive money --}}
                                        <div class="form-group">
                                            <label>Receive money</label>
                                            <span class="glyphicon glyphicon-question-sign" id="receiving-payment-popover"></span>

                                            <div class="radio-button-group">
                                                <label class="radio-inline">
                                                    <input type="radio" name="payout_method" id="payout_paypal" value="paypal"
                                                            {{ $product->payout_method == 'paypal' ? 'checked' : '' }}>
                                                    <span>PayPal</span>
                                                </label>

                                                <label class="radio-inline">
                                                    <input type="radio" name="payout_method" id="payout_cash" value="cash"
                                                            {{ $product->payout_method == 'cash' ? 'checked' : '' }}>
                                                    <span>Cash</span>
                                                </label>
                                            </div>
                                        </div>

                                        {{--Paypal account--}}
                                        <div class="form-group" id="paypal_account">
                                            <label class="full-width">
                                                <span>Paypal Account</span>
                                                <span class="pull-right">
                                                    <small>
                                                        <a href="https://www.paypal.com/us/signup/account" target="_blank">No Paypal account?</a>
                                                    </small>
                                                </span>
                                            </label>


                                            <input type="email" name="paypal" class="form-control"
                                                   value="{{ $paypal or '' }}" placeholder="Paypal email address">

                                            <div>
                                                <small class="text-success receiving-payment-info">
                                                    A PayPal service fee (2.9% + $0.30) will be deducted from your receiving payment.
                                                </small>
                                            </div>
                                        </div>

                                        {{-- Sell to --}}
                                        <div class="form-group">
                                            <div class="checkbox">
                                                <label for="">
                                                    <input type="checkbox" name="accept_trade_in" {{ $product->accept_trade_in ? 'checked' : '' }}>
                                                    I would like to join the Stuvi Book Trade-In program
                                                    <span class="glyphicon glyphicon-question-sign" id="book-trade-in-popover"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <small>By posting your book, you agree to Stuvi's
                                                <a href="#" data-toggle="modal" data-target="#terms-modal">Terms of Service</a>
                                                and <a href="#" data-toggle="modal" data-target="#privacy-modal"> Privacy Notice</a>.</small>
                                        </div>

                                        <input type="submit" name="submit" class="btn btn-lg btn-primary center-block" value="Update">
                                    </div>
                                </div>
                            </section>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>

@endsection
