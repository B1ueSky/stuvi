@extends('layouts.admin')

@section('title', 'User')

@section('content')

    <table class="table table-condensed" data-sortable>
        <thead>
            <tr class="active">
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Primary Email</th>
                <th>Phone Number</th>
                <th>Activated</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->primaryEmailAddress() }}</td>
                    <td>{{ $user->phone_number }}</td>
                    <td>{{ $user->isActivatedToStr() }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>
                        <a href="{{ url('admin/user/' . $user->id) }}"><span class="glyphicon glyphicon-eye-open"></span></a>
                        <a href="{{ url('admin/user/' . $user->id . '/edit') }}"><span class="glyphicon glyphicon-edit"></span></a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    {!! $users->appends($pagination_params)->render() !!}
@endsection
