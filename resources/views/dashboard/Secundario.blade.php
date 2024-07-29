@extends('layouts.user_type.auth')

@section('content')

@section('content')
    @php
        $user = Auth::user();
        $permissions = $user->getAllPermissions();
    @endphp

    @if($permissions->isEmpty())
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <h2>No hay permisos asignados, comunicarse con Sistemas.</h2>
        </div>
    @endif
@endsection

@endsection