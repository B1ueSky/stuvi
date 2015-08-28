{{--User Email page--}}


@extends('layouts.textbook')
@section('title', 'Email - '.Auth::user()->first_name.' '.Auth::user()->last_name )

@section('content')
    <div class="container page-content">
        {{-- Left nav--}}
        <div class="col-md-3 col-sm-4">
            <ul class="nav nav-pills nav-stacked">
                <li role="presentation"><a href="{{ url('user/profile') }}">Profile Settings</a></li>
                <li role="presentation"><a href="{{ url('user/account') }}">Account Settings</a></li>
                <li role="presentation" class="active"><a href="{{ url('user/email') }}">Email Settings</a></li>
                <li role="presentation"><a href="{{ url('user/bookshelf') }}">Bookshelf</a></li>
            </ul>
        </div>

        {{-- Right content --}}
        <div class="col-md-6 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Email settings</h3>
                </div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <table class="table table-no-border">
                            <caption>Stuvi will send all notification emails to your primary email.</caption>
                            <thead>
                                <tr>
                                    <th>Emails</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            @foreach ($emails as $email)
                                <tr>
                                    <td>{{ $email->email_address }}</td>
                                    @if ($email->isPrimary())
                                        <td>
                                            Primary
                                        </td>
                                        <td></td>
                                    @else
                                        @if (!$email->verified)
                                            <td>
                                                Unverified
                                            </td>
                                        @else
                                            <td>
                                                <form action="{{ url('/user/email/primary') }}" method="post">
                                                    {!! csrf_field() !!}
                                                    <input type="hidden" name="id" value="{{ $email->id }}">
                                                    <input type="submit" class="btn btn-primary" value="Set as primary">
                                                </form>
                                            </td>
                                        @endif
                                        <td>
                                            @if (!$email->isCollegeEmail())
                                                <form action="{{ url('/user/email/remove') }}" method="post">
                                                    {!! csrf_field() !!}
                                                    <input type="hidden" name="id" value="{{ $email->id }}">
                                                    <input type="submit" class="btn btn-primary" value="Remove">
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                        {{-- Add an email --}}
                        <form action="{{ url('/user/email/add') }}" method="post" class="form-inline">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Add a new email" value="{{ old('email') }}">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
