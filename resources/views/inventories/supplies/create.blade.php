@extends('layouts.user_type.auth')

@section('content')
<div class="p-3">
    <h3>Crear Insumo</h3>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('supplies.store') }}" method="POST">
        @csrf
        <div class="card border border-dark p-3 my-3">
            <div class="card-header">General
                <hr>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <input type="hidden" name="category_id" value="{{ $categoria->id }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>

                    <!-- Fabricante -->
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

                    <!-- Modelo -->
                    <div class="mb-3">
                        <label for="modelo_id" class="form-label">Modelo</label>
                        <div class="d-flex align-items-center">
                            <!-- Select2 Modelos -->
                            <select id="modelo_id" name="model_id" class="form-control js-select-modelo" style="width: 90%;" data-show-subtext="true" data-live-search="true" required>
                                <option value="" disabled selected>Seleccione un modelo</option>
                            </select>

                            <!-- Botón para agregar nuevo modelo -->
                            <button type="button" class="btn btn-green-600 ms-2 m-auto" title="agregar modelo" id="addModeloBtn" disabled data-bs-toggle="modal" data-bs-target="#createModeloModal" style="height: 38px;">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                </div>
                <div class="col-lg-5 d-flex align-items-center justify-content-center">
                    <img src="{{ asset( $categoria->image) }}" alt="Categoría" class="img-fluid rounded shadow" style="max-width: 300px; height: auto; border: 2px solid #ccc; padding: 10px;">
                </div>
            </div>
        </div>

        <div class="card border border-dark p-3 my-3">
            <div class="card-header">Especificos</div>
            <hr>
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <label for="quantity" class="form-label">Cantidad</label>
                    <input type="number" name="quantity" class="form-control" id="quantity" required>
                </div>

                <div class="col-lg-4 mb-3">
                    <label for="unit" class="form-label">Unidad</label>
                    <input type="text" name="unit" class="form-control" id="unit" required>
                </div>

                <div class="col-lg-4 mb-3">
                    <label for="status" class="form-label">Estado</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="active">Activo</option>
                        <option value="inactive">Inactivo</option>
                    </select>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea name="description" class="form-control" id="description" rows="3"></textarea>
                </div>
                <div class="col-6 text-end align-self-end">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>

            </div>

        </div>

</div>




</form>


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
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
                <button id="closeModalButton" type="button" class="btn btn-secondary">Cancelar</button></button>
            </div>
        </div>
    </div>
</div>


<div id="tableContainer"></div>

<!-- Modal para crear modelo -->
<div class="modal fade" id="createModeloModal" tabindex="-1" aria-labelledby="createModeloModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModeloModalLabel">Añadir Nuevo Modelo para <span id="selectedFabricante"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createModeloForm">
                    <input type="hidden" id="fabricante_id_modelo" name="fabricante_id_modelo">
                    <div class="mb-3">
                        <label for="nuevo_modelo_nombre" class="form-label">Nombre del Modelo</label>
                        <input type="text" class="form-control" id="nuevo_modelo_nombre" name="nombre" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
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

        initializeSelect2('#category_id');
        initializeSelect2('#fabricante_id');
        initializeSelect2('#modelo_id');

        function cargarModelosPorFabricante(fabricanteId) {
            if (fabricanteId) {
                $.ajax({
                    url: '/modelos/por-fabricante/' + fabricanteId,
                    method: 'GET',
                    success: function(response) {
                        let $modeloSelect = $('#modelo_id').empty();
                        response.modelos.forEach(function(modelo) {
                            $modeloSelect.append(new Option(modelo.name, modelo.id));
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudieron cargar los modelos.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            } else {
                $('#modelo_id').empty().append('<option value="" disabled selected>Seleccione un modelo</option>');
            }
        }



        $('#fabricante_id').on('change', function() {
            let fabricanteId = $(this).val();
            let fabricanteText = $(this).find('option:selected').text();

            $('#addModeloBtn').prop('disabled', !fabricanteId);
            $('#selectedFabricante').text(fabricanteText);
            $('#fabricante_id_modelo').val(fabricanteId);

            // Cargar modelos cuando el fabricante cambia
            cargarModelosPorFabricante(fabricanteId);
        });


        // Manejo del formulario para crear un nuevo fabricante
        $('#createFabricanteForm').on('submit', function(e) {
            e.preventDefault();
            let nombre = $('#nuevo_fabricante_nombre').val();

            $.ajax({
                url: "{{ route('fabricantes.store') }}",
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    nombre: nombre,
                    type: 'Insumo',

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

        // Manejo del formulario para crear un nuevo modelo
        $('#createModeloForm').on('submit', function(e) {
            e.preventDefault();
            let nombre = $('#nuevo_modelo_nombre').val();
            let fabricanteId = $('#fabricante_id_modelo').val();

            $.ajax({
                url: "{{ route('modelos.store') }}",
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    nombre: nombre,
                    fabricante_id: fabricanteId
                },
                success: function(response) {


                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Nuevo modelo creado con éxito.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });

                    $('#createModeloModal').modal('hide');
                    $('#nuevo_modelo_nombre').val('');
                    cargarModelosPorFabricante(fabricanteId);
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un error al crear el modelo.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });

    });
</script>
@endsection