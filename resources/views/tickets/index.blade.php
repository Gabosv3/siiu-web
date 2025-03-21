@extends('layouts.user_type.auth')

@section('content')
<div class="mt-4">
    <h1 class="mb-4 text-center">Tickets</h1>

    <div class="d-flex flex-wrap justify-content-between mb-4">
        <a href="{{ route('vista.tickets') }}" class="btn btn-primary btn-lg mb-2 mb-md-0 w-100 w-md-auto">Crear Ticket</a>

        <div class="d-flex flex-wrap justify-content-between w-100 w-md-auto">
            <button type="button" class="btn btn-green-600 btn-lg mb-2 mb-md-0 w-100 w-md-auto" data-bs-toggle="modal"
                data-bs-target="#createTitleModal">
                Crear Título
            </button>
        </div>
    </div>

    <!-- Filtros -->
<div class="card shadow-lg p-4 mb-4 bg-body rounded">
    <form method="GET" action="{{ route('tickets.index') }}">
        <div class="row g-4">
            <div class="col-md-4">
                <label for="status" class="form-label fw-bold">Estado</label>
                <select name="status" id="status" class="form-select form-select-lg">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en proceso" {{ request('status') == 'en proceso' ? 'selected' : '' }}>En Proceso</option>
                    <option value="resuelto" {{ request('status') == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="departamento" class="form-label fw-bold">Departamento</label>
                <select name="departamento" id="departamento" class="form-select form-select-lg">
                    <option value="">Todos</option>
                    @foreach ($departamentos as $departamento)
                    <option value="{{ $departamento->id }}" {{ request('departamento') == $departamento->id ? 'selected' : '' }}>
                        {{ $departamento->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="tecnico" class="form-label fw-bold">Técnico</label>
                <select name="tecnico" id="tecnico" class="form-select form-select-lg">
                    <option value="">Todos</option>
                    @foreach ($tecnicos as $tecnico)
                    <option value="{{ $tecnico->id }}" {{ request('tecnico') == $tecnico->id ? 'selected' : '' }}>
                        {{ $tecnico->user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-end gap-3">
            <button type="submit" class="btn btn-primary btn-lg px-4">Filtrar <i class="fas fa-filter"></i></button>
            <a href="{{ route('tickets.index') }}" class="btn btn-secondary btn-lg px-4">Restablecer <i class="fas fa-sync-alt"></i></a>
        </div>
    </form>
</div>


    <div class="card shadow-lg p-3 mb-5 bg-body rounded">
        <table id="Principal" class="table table-striped table-bordered table-hover align-items-center mb-0">
            <thead class="table-dark ">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Estado</th>
                    <th>Departamento</th>
                    <th>Técnico asignado</th>
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
                    <td>{{ $ticket->user->departament->name }}</td>
                    <td>{{ $ticket->technician ? $ticket->technician->user->name : 'Técnico no asignado' }}</td>
                    <td>{{ $ticket->created_at }}</td>
                    <td>
                        <div class="d-flex justify-content-start">
                            <a href="{{ route('tickets.assignForm', $ticket) }}" class="btn btn-cyan-800  mb-2 me-2" title="Asignar Ticket">
                                <i class="fa fa-tasks"> </i> Asignar
                            </a>
                            <button onclick="printSingleTicket({{ $ticket->id }}, '{{ $ticket->title->name }}', '{{ $ticket->status }}', '{{ $ticket->created_at }}')" class="btn btn-red-800 mb-2">
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
    <div class="modal fade" id="createTitleModal" tabindex="-1" aria-labelledby="createTitleModalLabel"
        aria-hidden="true">
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
                    name: name // Asegúrate de que `name` esté definido en tu contexto
                })
            })
            .then(response => {
                // Si la respuesta no es exitosa (por ejemplo, código 422)
                if (!response.ok) {
                    return response.json().then(err => {
                        // Lanza un objeto con el código de estado y los errores
                        throw {
                            status: response.status,
                            errors: err.errors
                        };
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Título creado con éxito!',
                        text: 'Ahora puedes encontrarlo en la barra de creación de tickets.',
                        confirmButtonText: 'Entendido'
                    }).then(() => {
                        location.reload(); // Recarga la página para actualizar la tabla
                    });
                }
            })
            .catch(error => {
                // Captura errores de validación (422) o errores del servidor
                if (error.status === 422) {
                    // Si hay errores de validación, los mostramos
                    let errorMessage = Object.values(error.errors).join('<br>');
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error de validación!',
                        html: `Hubo un problema con los datos ingresados:<br>${errorMessage}`,
                        confirmButtonText: 'Cerrar'
                    });
                } else {
                    // Otros errores (por ejemplo, error 500)
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error al crear el título!',
                        text: 'Hubo un problema al guardar el título. Por favor, intenta de nuevo.',
                        confirmButtonText: 'Cerrar'
                    });
                }
            });

    });

    function printSingleTicket(id, title, status, created_at) {
        let printWindow = window.open('', '', 'width=1200,height=900');
        printWindow.document.write('<html><head>');
        printWindow.document.write('<style>');

        // Definir el tamaño de la página para impresión
        printWindow.document.write('@page { size: 50mm 45mm; margin: 0; }');

        // Estilos para el cuerpo del ticket
        printWindow.document.write(
            'body { font-family: Arial, sans-serif; margin: 0; padding: 0; width: 50mm; font-size: 12px; }');

        // Estilo del título
        printWindow.document.write(
            'h2 { color: #004085; border-bottom: 2px solid #004085; padding-bottom: 5px; font-size: 14px; text-align: center; }'
        );

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
<script>
    $(document).ready(function() {
        // Inicializar Select2 en los campos Departamento y Técnico
        $('#departamento').select2({

            placeholder: "Seleccione un departamento",
            theme: "bootstrap-5",
            allowClear: true
        });

        $('#tecnico').select2({
            placeholder: "Seleccione un técnico",
            theme: "bootstrap-5",
            allowClear: true
        });
    });
</script>
@endsection