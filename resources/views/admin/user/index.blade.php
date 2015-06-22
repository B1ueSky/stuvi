@extends('admin')

@section('content')
<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Actions</th>
    </tr>

    @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->first_name }}</td>
            <td>{{ $user->last_name }}</td>
            <td><a class="btn btn-default" role="button" href="{{ URL::to('admin/user/' . $user->id) }}">View Details</a></td>
        </tr>
    @endforeach
</table>
@endsection
