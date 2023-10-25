@extends('layouts.app')

@section('title', 'Details Page')

@section('content')
<h1>User Details</h1>

<table>
    <tr>
        <th>Name</th>
        <td>{{ $user->name }}</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>{{ $user->email }}</td>
    </tr>
    <tr>
        <th>Address</th>
        <td>{{ $user->address }}</td>
    </tr>
    <tr>
        <th>Phone</th>
        <td>{{ $user->phone }}</td>
    </tr>
</table>
@endsection