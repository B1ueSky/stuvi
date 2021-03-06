@if(count(Auth::user()->addresses) < 1)
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Enter a new pickup address</h3>
        </div>

        <div class="panel-body">
            <form action="{{ url('address/store') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="">Full name</label>
                    <input type="text" class="form-control" name="addressee">
                </div>

                <div class="form-group">
                    <label for="">Address line 1</label>
                    <input type="text" class="form-control" name="address_line1">
                </div>

                <div class="form-group">
                    <label for="">Address line 2</label>
                    <input type="text" class="form-control"
                           name="address_line2" placeholder="Apartment, suite, unit, building, etc.">
                </div>

                <div class="form-group">
                    <label for="">City</label>
                    <input type="text" class="form-control" name="city">
                </div>

                <div class="form-group">
                    <label for="">State</label>
                    <input type="text" class="form-control" name="state_a2">
                </div>

                <div class="form-group">
                    <label for="">ZIP</label>
                    <input type="text" class="form-control" name="zip">
                </div>

                <div class="form-group">
                    <label for="">Phone number</label>
                    <input type="text" class="form-control" name="phone_number">
                </div>

                <input type="submit" class="btn btn-primary" value="Use this address">
            </form>
        </div>
    </div>
@else
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Choose a pickup address</h3>
        </div>

        <ul class="list-group">

            @foreach(Auth::user()->addresses as $address)
                <li class="list-group-item">
                    <ul class="no-bullet no-padding-left">
                        <li>{{ $address->addressee }}</li>
                        <li>
                            {{ $address->address_line1 }}
                            @if($address->address_line2)
                                , {{ $address->address_line2 }}
                            @endif
                        </li>
                        <li>{{ $address->city }}, {{ $address->state_a2 }} {{ $address->zip }}</li>
                        <li>
                            {{ $address->phone_number }}
                            <a href="#edit-address" data-toggle="modal"
                               data-address_id="{{ $address->id }}"
                               data-addressee="{{ $address->addressee }}"
                               data-address_line1="{{ $address->address_line1 }}"
                               data-address_line2="{{ $address->address_line2 }}"
                               data-city="{{ $address->city }}"
                               data-state_a2="{{ $address->state_a2 }}"
                               data-zip="{{ $address->zip }}"
                               data-phone_number="{{ $address->phone_number }}">
                                Edit
                            </a>
                        </li>
                        <br>
                        <li>
                            @if($address->is_default)
                                <button class="btn btn-primary disabled">Selected address</button>
                            @else
                                <form action="{{ url('address/select') }}" method="post" class="no-margin-bottom">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="selected_address_id" value="{{ $address->id }}">
                                    <input type="submit" class="btn btn-primary" value="Use this address">
                                </form>
                            @endif
                        </li>
                    </ul>
                </li>
            @endforeach

            {{-- Add a new address --}}
            <li class="list-group-item">
                <a href="#add-address" data-toggle="modal">
                    <span class="glyphicon glyphicon-plus"></span> Add a new address
                </a>
            </li>
        </ul>
    </div>


@endif

@include('includes.modal.add-address')
@include('includes.modal.edit-address')