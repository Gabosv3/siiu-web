@extends('layouts.user_type.auth')

@section('content')
    <h1>Tickets</h1>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">Crear Ticket</a>
    <table class="table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Técnico Asignado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->titulo }}</td>
                    <td>{{ $ticket->estado }}</td>
                    <td>{{ $ticket->prioridad }}</td>
                    <td>{{ $ticket->tecnico ? $ticket->tecnico->nombre : 'No asignado' }}</td>
                    <td>
                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-info">Ver</a>
                        <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-warning">Editar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
