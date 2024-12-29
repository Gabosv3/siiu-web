@extends('layouts.user_type.auth')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Crear Modelo</div>
                <div class="card-body">
                    <form id="createModeloForm" method="POST" action="{{ route('models.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="fabricante_id" class="form-label">Fabricante</label>
                            <div class="d-flex align-items-center">
                                <!-- Select2 Fabricante -->
                                <select id="fabricante_id" name="manufacturer_id" class="form-control js-select-fabricante" style="width: 90%;" required>
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
                            <label for="name_modelo">Nombre del modelo:</label>
                            <input type="text" id="name_modelo" name="name_modelo" class="form-control">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                    @csrf
                    <div class="mb-3">
                        <label for="nuevo_fabricante_nombre" class="form-label">Nombre del Fabricante</label>
                        <input type="text" class="form-control" id="nuevo_fabricante_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Categoria</label>
                        <select id="type" name="type" class="form-control" required>
                            <option value="" disabled selected> Seleccione una categoria</option>
                            <option value="Equipo">Equipo</option>
                            <option value="Insumo">Insumo</option>
                            <option value="Software">Software</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        function initializeSelect2(selector) {
            $(selector).select2({
                placeholder: "Seleccione una opción",
                theme: "bootstrap-5",
                width: '100%',
            });
        }

        initializeSelect2('#fabricante_id');

        $('#createFabricanteForm').on('submit', function(e) {
            e.preventDefault();
            let nombre = $('#nuevo_fabricante_nombre').val();
            let type = $('#type').val();

            $.ajax({
                url: "{{ route('fabricantes.store') }}",
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    nombre: nombre,
                    type: type,

                },
                success: function(response) {
                    // Mensaje de éxito
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Nuevo fabricante creado con éxito.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });

                    // Cierra el modal

                    $('#createFabricanteModal').modal('hide');
                    $('#nuevo_fabricante_nombre').val('');

                    // Limpia el campo de entrada
                    // Crea una nueva opción para el select de fabricantes
                    let newOption = new Option(response.nombre, response.id, true, true); // Suponiendo que tu respuesta tiene 'nombre' y 'id'
                    $('#fabricante_id').append(newOption).trigger('change'); // Asegúrate de que el ID del select sea correcto


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

        $('#createModeloForm').on('submit', function(event) {
            event.preventDefault();
            let nombre = $('#name_modelo').val();
            let fabricanteId = $('#fabricante_id').val();
            $.ajax({
                url: "{{ route('models.store') }}",
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    nombre: nombre,
                    fabricante_id: fabricanteId
                },
                
                success: function(response) {
                    if (response.status == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Modelo creado con éxito.',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    });
</script>
@endsection