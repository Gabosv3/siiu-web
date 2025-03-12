@extends('layouts.user_type.auth')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">Tickets</h1>

    <div class="d-flex flex-wrap justify-content-between mb-4">
        <a href="{{ route('vista.tickets') }}" class="btn btn-primary btn-lg mb-2 mb-md-0 w-100 w-md-auto">Crear Ticket</a>
        <div class="d-flex flex-wrap justify-content-between w-100 w-md-auto">
            <!-- Botón para abrir el modal -->
            <button type="button" class="btn btn-success btn-lg mb-2 mb-md-0 w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#createTitleModal">
                Crear Título
            </button>

        </div>
    </div>

    <div class="card shadow-lg p-3 mb-5 bg-body rounded">
        <table id="Principal" class="table table-striped table-bordered table-hover align-items-center mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Estado</th>
                    <th>Fecha de Creación</th>
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
                        <div class="d-flex justify-content-start">
                            <a href="{{ route('tickets.assignForm', $ticket) }}" class="btn btn-info mb-2 me-2" title="Asignar Ticket">
                                <i class="fa fa-tasks"></i>
                            </a>
                            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-warning mb-2 me-2">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button onclick="printSingleTicket({{ $ticket->id }}, '{{ $ticket->title->name }}', '{{ $ticket->status }}', '{{ $ticket->created_at }}')" class="btn btn-danger mb-2">
                                <i class="fa fa-print"></i> Imprimir
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $tickets->links() }}
        </div>
    </div>

    <!-- Modal para crear título -->
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
                        <button type="submit" class="btn btn-primary w-100">Guardar</button>
                    </form>
                </div>
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

        fetch("{{ route('titles.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    name: name
                })
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

    function printSingleTicket(id, title, status, created_at) {
        let printWindow = window.open('', '', 'width=1200,height=900');
        printWindow.document.write('<html><head>');
        printWindow.document.write('<style>');

        // Definir el tamaño de la página para impresión
        printWindow.document.write('@page { size: 50mm 45mm; margin: 0; }');

        // Estilos para el cuerpo del ticket
        printWindow.document.write('body { font-family: Arial, sans-serif; margin: 0; padding: 0; width: 50mm; font-size: 12px; }');

        // Estilo del título
        printWindow.document.write('h2 { color: #004085; border-bottom: 2px solid #004085; padding-bottom: 5px; font-size: 14px; text-align: center; }');

        // Estilo de los párrafos
        printWindow.document.write('p { font-size: 12px; line-height: 1.5; text-align: left; margin: 5px 0; }');

        // Estilos específicos para la impresión
        printWindow.document.write('@media print { body { margin: 0; padding: 0; } }');

        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');

        // Contenido del ticket
        printWindow.document.write('<h2>Detalles del Ticket</h2>');
        printWindow.document.write('<p><strong>Ticket Numero:</strong> ' + id + '</p>');
        printWindow.document.write('<p><strong>Título:</strong> ' + title + '</p>');
        printWindow.document.write('<p><strong>Estado:</strong> ' + status + '</p>');
        printWindow.document.write('<p><strong>Fecha de creación:</strong> ' + created_at + '</p>');

        printWindow.document.write('</body></html>');
        printWindow.document.close();

        // Ejecutar la impresión
        printWindow.print();
    }
</script>
@endsection