@extends('layouts.admin')

@section('main-menu')
    <li class="active"><a href="users">Users</a></li>
@endsection

@section('content')

    <table class="highlight">
        <thead>
        <tr>
            <th>Email</th>
            <th>Name</th>
            <th>Role</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr id="{{ $user['id'] }}">
                <td> {{ $user['email'] }} </td>
                <td> {{ $user['name'] }} </td>
                <td> {{ $user['role'] }} </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection