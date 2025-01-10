@extends('layouts.user_type.auth')

@section('content')

<div class="card mt-4 p-3">
    <h2>Reportes Usuarios</h2>
    @foreach ($users as $user)
        <p>{{ $user->name }}</p>
        <p>{{ $user->email }}</p>
    @endforeach
</div>

@endsection
