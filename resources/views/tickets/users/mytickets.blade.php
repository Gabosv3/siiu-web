@extends('layouts.user_type.auth')

@section('content')

<div class="container">
    <h1>Mis Tickets</h1>

    @if($tickets->isEmpty())
        <p>No tienes tickets creados.</p>
    @else
    <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
        <table id="Principal" class="table align-items-center mb-0 text-center" style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Estado</th>
                    <th>Fecha de creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->status }}</td>
                    <td>{{ $ticket->created_at }}</td>
                    <td>
                        <!-- Cambia a un enlace que redirija a la vista de detalles -->
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-cyan-800 mb-3"><i class='bx bxs-show'></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@include('components.script-btn') <!-- Incluir scripts necesarios -->
<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>

@endsection