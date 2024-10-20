@extends('layouts.user_type.auth')

@section('content')
    <h1>Tickets</h1>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">Crear Ticket</a>
    <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
    <table id="Principal" class="table align-items-center mb-0">
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
                    <td>{{ $ticket->created_at }}</td> <td>
                        <a href="{{ route('tickets.assignForm', $ticket) }}" class="btn btn-cyan-800 mb-2" title="Asignar Ticket"><li class="fa fa-tasks"></li></a>
                        <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-green-600 mb-2"><li class="fa fa-edit"></li></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @include('components.script-btn')
    <script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>
@endsection
