{{--/textbook/sell/product/create/#--}}

@extends('layouts.textbook')

@section('title', 'Enter Product Info')

@section('css')
    <link rel="stylesheet" href="{{ asset('libs/dropzone/dist/min/dropzone.min.css') }}">
@endsection

@section('content')

    <div class="container">

        <div class="row">
            {{-- form --}}
            <div class="col-md-8 col-md-offset-2">
                @if(Auth::check())
                    <form id="form-product" class="dropzone">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="book_id" value="{{ $book->id }}"/>
                        <input type="hidden" name="book_title" value="{{ $book->title }}">

                        <section class="details">
                            <div class="panel panel-presentation">
                                <div class="panel-heading">
                                    <h2>Complete Book Details</h2>
                                </div>

                                <div class="panel-body">
                                    <div class="container-fluid">

                                        {{--General Condition--}}
                                        <div class="form-group">
                                            <label>{{ Config::get('product.conditions.general_condition.title') }}</label>
                                            {{--Open modal button--}}
                                            <i class="fa fa-question-circle" data-toggle="modal"
                                               data-target=".condition-modal"></i>
                                            <br>
                                            {{--General Condition Modal--}}
                                            <div class="modal fade condition-modal" tabindex="-1" role="dialog"
                                                 aria-labelledby="General Conditions">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close close-modal-btn"
                                                                    data-dismiss="modal" aria-label="close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            <h3>{{ Config::get('product.conditions.general_condition.title') }}</h3>
                                                        </div>
                                                        <div class="modal-body">
                                                            @for ($i = 0; $i < 4; $i++)
                                                                <dl>
                                                                    <dt>{{ Config::get('product.conditions.general_condition')[$i] }}</dt>
                                                                    <dd>{{ Config::get('product.conditions.general_condition.description')[$i] }}</dd>
                                                                </dl>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--General Conditon Buttons--}}
                                            <div class="btn-group btn-group-justified" data-toggle="buttons">
                                                @for ($i = 0; $i < 4; $i++)
                                                    <label class="btn btn-default">
                                                        <input type="radio" name="general_condition"
                                                               value="{{$i}}"> {{ Config::get('product.conditions.general_condition')[$i] }}
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>

                                        {{--Highlights/Notes--}}
                                        <div class="form-group">
                                            <label>{{ Config::get('product.conditions.highlights_and_notes.title') }}</label>
                                            {{--Open modal button--}}
                                            <i class="fa fa-question-circle" data-toggle="modal"
                                               data-target=".highlight-modal"></i>
                                            <br>
                                            {{--Highlights / Notes modal--}}
                                            <div class="modal fade highlight-modal" tabindex="-1" role="dialog"
                                                 aria-labelledby="Highlights/Notes">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close close-modal-btn"
                                                                    data-dismiss="modal" aria-label="close">
                                                                <span id="close-span" aria-hidden="true">&times;</span>
                                                            </button>
                                                            <h3>{{ Config::get('product.conditions.highlights_and_notes.title') }}</h3>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ Config::get('product.conditions.highlights_and_notes.description') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--Highlights / Notes buttons--}}
                                            <div class="btn-group btn-group-justified" data-toggle="buttons">
                                                @for ($i = 0; $i < 3; $i++)
                                                    <label class="btn btn-default">
                                                        <input type="radio" name="highlights_and_notes"
                                                               value="{{$i}}"> {{ Config::get('product.conditions.highlights_and_notes')[$i] }}
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>

                                        {{--Damaged Pages--}}
                                        <div class="form-group">
                                            <label>{{ Config::get('product.conditions.damaged_pages.title') }}</label>
                                            <i class="fa fa-question-circle" data-toggle="modal"
                                               data-target=".damage-modal"></i>
                                            <br>
                                            {{--Damaged pages modal--}}
                                            <div class="modal fade damage-modal" tabindex="-1" role="dialog"
                                                 aria-labelledby="Damaged Pages">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close close-modal-btn"
                                                                    data-dismiss="modal" aria-label="close">
                                                                <span id="close-span" aria-hidden="true">&times;</span>
                                                            </button>
                                                            <h3>{{ Config::get('product.conditions.damaged_pages.title') }}</h3>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ Config::get('product.conditions.damaged_pages.description') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--Damaged pages buttons--}}
                                            <div class="btn-group btn-group-justified" data-toggle="buttons">
                                                @for($i = 0; $i < 3; $i++)
                                                    <label class="btn btn-default">
                                                        <input type="radio" name="damaged_pages"
                                                               value="{{$i}}"> {{ Config::get('product.conditions.damaged_pages')[$i] }}
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>

                                        {{--Broken Binding--}}
                                        <div class="form-group">
                                            <label>{{ Config::get('product.conditions.broken_binding.title') }}</label>
                                            <i class="fa fa-question-circle" data-toggle="modal"
                                               data-target=".binding-modal"></i>
                                            <br>
                                            {{--Broken binding modal--}}
                                            <div class="modal fade binding-modal" tabindex="-1" role="dialog"
                                                 aria-labelledby="Broken Binding">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close close-modal-btn"
                                                                    data-dismiss="modal"
                                                                    aria-label="close">
                                                                <span id="close-span" aria-hidden="true">&times;</span>
                                                            </button>
                                                            <h3>{{ Config::get('product.conditions.broken_binding.title') }}</h3>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>{{ Config::get('product.conditions.broken_binding.description') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--Broken binding buttons--}}
                                            <div class="btn-group btn-group-justified" data-toggle="buttons">
                                                @for($i = 0; $i < 2; $i++)
                                                    <label class="btn btn-default">
                                                        <input type="radio" name="broken_binding"
                                                               value="{{$i}}"> {{ Config::get('product.conditions.broken_binding')[$i] }}
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>

                                        {{--Description--}}
                                        <div class="form-group">
                                            {{--<label>{{ Config::get('product.conditions.description.title') }}</label>--}}
                                            <textarea name="description" class="form-control" rows="5"
                                                      placeholder="{{ Config::get('product.conditions.description.placeholder') }}"></textarea>
                                        </div>

                                        {{--Upload Images using Dropzone--}}
                                        <div class="form-group">
                                            {{--<label>Upload Textbook Image</label>--}}

                                            <div id="dropzone-img-preview" class="dropzone-previews dz-clickable">
                                                <div class="dz-message">
                                                    <h4>Drop or click here to upload textbook images.</h4>
                                                    <br>
                                                    <small class="text-muted">(A front cover image is required. You can upload a maximum of
                                                        three images, at most 3MB per image.)
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="ready">
                            <div class="panel panel-presentation">
                                <div class="panel-heading">
                                    <h2>Ready to Go!</h2>
                                </div>
                                <div class="panel-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-sm-8 col-sm-offset-2">
                                                {{--your price--}}
                                                <div class="form-group">
                                                    <label>Sale Price</label>

                                                    <div class="input-group">
                                                        <div class="input-group-addon">$</div>
                                                        <input type="number" step="0.01" min="0.00" name="price"
                                                               class="form-control" placeholder="Set the sale price for your book">
                                                    </div>
                                                </div>

                                                {{--Paypal account--}}
                                                <div class="form-group">
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
                                                </div>

                                                <div class="form-group">
                                                    <small>By posting your book, you agree to Stuvi's
                                                        <a href="#" data-toggle="modal" data-target="#terms-modal">Terms of Service</a>
                                                        and <a href="#" data-toggle="modal" data-target="#privacy-modal"> Privacy Notice</a>.</small>
                                                </div>

                                                <input type="submit" name="submit" class="btn btn-primary btn-block" value="Upload images and post book">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </section>
                    </form>
                @endif


            </div>
        </div>
    </div>

@endsection

@section('modals')
    @include('includes.textbook.tos-privacy-modal')
@endsection

@section('javascript')
    <script src="{{ asset('libs/dropzone/dist/min/dropzone.min.js') }}"></script>

    @if(Auth::check())
        {{-- FormValidation --}}
        {{--<script src="{{ asset('libs-paid/formvalidation-dist-v0.6.3/dist/js/formValidation.min.js') }}"></script>--}}
        {{--<script src="{{ asset('libs-paid/formvalidation-dist-v0.6.3/dist/js/framework/bootstrap.min.js') }}"></script>--}}
        <script src="{{ asset('js/product/create.js') }}"></script>
    @endif
@endsection
