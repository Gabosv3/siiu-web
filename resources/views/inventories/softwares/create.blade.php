@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Agregar Nuevo Software</h1>

    <form action="{{ route('softwares.store') }}" method="POST">
        @csrf
        <!-- Fabricante -->
        <div class="mb-3">
            <label for="fabricante_id" class="form-label">Fabricante</label>
            <div class="d-flex align-items-center">
                <!-- Select2 Fabricante -->
                <select id="fabricante_id" name="manufacturer_id" class="form-control js-select-fabricante" style="width: 90%;">
                    <option value="" disabled selected>Seleccione un fabricante</option>
                    @foreach($fabricantes as $fabricante)
                    <option value="{{ $fabricante->id }}">{{ $fabricante->name }}</option>
                    @endforeach
                </select>

                <!-- Botón para agregar nuevo fabricante -->
                <button type="button" class="btn btn-green-600 ms-2 m-auto" title="agregar fabricante" data-bs-toggle="modal" data-bs-target="#createFabricanteModal" style="height: 38px;">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="form-group">
            <label for="software_name">Nombre del Software</label>
            <input type="text" name="software_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="version">Versión</label>
            <input type="text" name="version" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Descripción</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
</div>

<!-- Modal para crear fabricante -->
<div class="modal fade" id="createFabricanteModal" tabindex="-1" aria-labelledby="createFabricanteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFabricanteModalLabel">Añadir Nuevo Fabricante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createFabricanteForm">
                    <div class="mb-3">
                        <label for="nuevo_fabricante_nombre" class="form-label">Nombre del Fabricante</label>
                        <input type="text" class="form-control" id="nuevo_fabricante_nombre" name="nombre" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

// Inicializar Select2
function initializeSelect2(selector) {
    $(selector).select2({
        placeholder: "Seleccione una opción",
        theme: "bootstrap-5",
        width: '100%',
    });
}

initializeSelect2('#fabricante_id');

// Manejo del formulario para crear un nuevo fabricante
$('#createFabricanteForm').on('submit', function(e) {
    e.preventDefault();
    let nombre = $('#nuevo_fabricante_nombre').val();

    $.ajax({
        url: "{{ route('fabricantes.store') }}",
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            nombre: nombre
        },
        success: function(response) {
            // Cierra el modal si es necesario
            $('#createFabricanteModal').modal('toggle');  // Asegúrate de tener el modal correcto

            // Limpia el campo de entrada
            $('#nuevo_fabricante_nombre').val('');

            // Crea una nueva opción para el select de fabricantes
            let newOption = new Option(response.nombre, response.id, true, true); // Suponiendo que tu respuesta tiene 'nombre' y 'id'
            $('#fabricante_id').append(newOption).trigger('change'); // Asegúrate de que el ID del select sea correcto
            console.error(xhr.responseText); // Muestra el error en la consola
            console.log(xhr.responseText);
            console.log('si llega');
            // Mensaje de éxito
            Swal.fire({
                title: '¡Éxito!',
                text: 'Nuevo fabricante creado con éxito.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        },
        error: function() {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un error al crear el fabricante.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        }
    });
});



});
</script>
@endsection