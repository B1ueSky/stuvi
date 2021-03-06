{{--Edit Profile page--}}

@extends('layouts.textbook')
@section('title', 'Edit Profile - '.Auth::user()->first_name.' '.Auth::user()->last_name)

@section('content')
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li class="active">Profile settings</li>
            </ol>
        </div>

        <div class="row page-content">
            {{-- Left nav--}}
            <div class="col-md-3 col-sm-4">
                @include('includes.textbook.settings-panel')
            </div>

            {{-- Right content --}}
            <div class="col-md-6 col-sm-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Personal information</h3>
                    </div>
                    <div class="panel-body">
                        <div class="container-fluid">
                            <form action="{{url('/user/profile/update')}}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <!-- First name -->
                                <div class="form-group">
                                    <label>First name</label>
                                    <input type="text" class="form-control" name="first_name" value="{{ Auth::user()->first_name }}">
                                </div>

                                <!-- Last name -->
                                <div class="form-group">
                                    <label>Last name</label>
                                    <input type="text" class="form-control" name="last_name" value="{{ Auth::user()->last_name }}">
                                </div>

                                <!-- Phone -->
                                <div class="form-group">
                                    <label>Phone number</label>
                                    <input type="tel" class="form-control phone_number" name="phone" value="{{ Auth::user()->phone_number }}">
                                </div>

                                {{-- Paypal Account --}}
                                <div class="form-group">
                                    <label>PayPal account</label>
                                    <input type="email" class="form-control" name="paypal" placeholder="PayPal email address" value="{{ $profile->paypal }}">
                                </div>

                                <!-- Sex -->
                                <div class="form-group">
                                    <label>Gender</label>

                                    <input type="radio" name="sex" value="male"
                                           @if ($profile->sex == 'male')
                                           checked
                                            @endif
                                            > Male
                                    <input type="radio" name="sex" value="female"
                                           @if ($profile->sex == 'female')
                                           checked
                                            @endif
                                            > Female
                                </div>

                                <!-- birthday -->
                                <div class="form-group">
                                    <label>Birthday</label>
                                    <input class="form-control" id="datetimepicker-profile-birthday" type="text" name="birth" value={{$profile->birthday}}>
                                </div>

                                <!-- title -->
                                {{--<div class="form-group">--}}
                                    {{--<label>Title</label>--}}
                                    {{--<input type="text" class="form-control" name="title" value="{{ $profile->title }}">--}}
                                {{--</div>--}}


                                <!-- Bio / About me -->
                                <div class="form-group">
                                    <label>Bio/About me</label>
                                    <textarea name="bio" class="form-control" rows="4">{{$profile->bio or ''}}</textarea>
                                </div>

                                <!-- School -->
                                {{--<div class="form-group">--}}
                                {{--<label>School/University</label>--}}
                                {{--<p class="form-control-static text-muted">{{$school->name}}</p>--}}
                                {{--</div>--}}

                                <!-- Grad -->
                                {{--<div class="form-group">--}}
                                    {{--<label>Expected Graduation</label>--}}

                                    {{--<input class="form-control" id="datetimepicker-profile-grad" type="text" name="grad" value={{$profile->graduation_date}}>--}}
                                {{--</div>--}}

                                <!-- Area of Study / Major -->
                                <div class="form-group">
                                    <label>Area of Study/Major</label>
                                    <input type="text" class="form-control" name="major" value="{{$profile->major}}">
                                </div>

                                <!-- facebook -->
                                <div class="form-group">
                                    <label>Facebook</label>

                                    <input type="url" class="form-control" name="facebook"value={{$profile->facebook}}>
                                </div>

                                <!-- Twitter -->
                                <div class="form-group">
                                    <label>Twitter</label>
                                    <input type="text" class="form-control" name="twitter" value={{$profile->twitter}}>
                                </div>

                                <div class="form-group">
                                    <label>LinkedIn</label>
                                    <input type="url" class="form-control" name="linkedin" value={{$profile->linkedin}}>
                                </div>

                                <!-- website -->
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="url" class="form-control" name="site" value={{$profile->website}}>
                                </div>

                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
