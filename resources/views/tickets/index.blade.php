@extends('layouts.user_type.auth')

@section('content')
    <h1>Tickets</h1>
    <a href="{{ route('vista.tickets') }}" class="btn btn-primary">Crear Ticket</a>
    <!-- Botón para abrir el modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTitleModal">
        Crear Título
    </button>
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
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->title->name }}</td>
                        <td>{{ $ticket->status }}</td>
                        <td>{{ $ticket->created_at }}</td>
                        <td>
                            <a href="{{ route('tickets.assignForm', $ticket) }}" class="btn btn-cyan-800 mb-2"
                                title="Asignar Ticket">
                                <li class="fa fa-tasks"></li>
                            </a>
                            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-green-600 mb-2">
                                <li class="fa fa-edit"></li>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal -->
<div class="modal fade" id="createTitleModal" tabindex="-1" aria-labelledby="createTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTitleModalLabel">Crear Nuevo Título</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createTitleForm">
                    @csrf
                    <div class="mb-3">
                        <label for="titleName" class="form-label">Nombre del Título</label>
                        <input type="text" class="form-control" id="titleName" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

        @include('components.script-btn')
        <script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>
        <script>
            document.getElementById('createTitleForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const name = document.getElementById('titleName').value;

                fetch('{{ route('titles.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ name: name })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Título creado con éxito');
                        location.reload(); // Recarga la página para actualizar la tabla
                    } else {
                        alert('Error al crear el título');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        </script>

        @endsection
