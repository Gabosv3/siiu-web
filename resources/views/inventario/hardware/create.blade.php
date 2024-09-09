@extends('layouts.user_type.auth')

@section('content')
<!-- Formulario principal -->
<div class="p-3">
    <h3>Crear Activo</h3>
    <form action="{{ route('hardwares.store') }}" method="POST">
        @csrf
        <!-- Estado -->
        <div class="card border border-dark p-3 my-3">
            <div class="card-header">General
                <hr>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <!-- Estado -->
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select id="estado" name="estado" class="form-control" required>
                            <option value="nuevo">Nuevo</option>
                            <option value="asignado">Asignado</option>
                        </select>
                    </div>

                    <!-- Fabricante -->
                    <div class="mb-3">
                        <label for="fabricante_id" class="form-label">Fabricante</label>
                        <div class="d-flex align-items-center">
                            <!-- Select2 Fabricante -->
                            <select id="fabricante_id" name="fabricante_id" class="form-control js-select-fabricante" style="width: 90%;">
                                <option value="" disabled selected>Seleccione un fabricante</option>
                                @foreach($fabricantes as $fabricante)
                                <option value="{{ $fabricante->id }}">{{ $fabricante->nombre }}</option>
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
                            <select id="modelo_id" name="modelo_id" class="form-control js-select-modelo" style="width: 90%;" data-show-subtext="true" data-live-search="true">
                                <option value="" disabled selected>Seleccione un modelo</option>
                            </select>

                            <!-- Botón para agregar nuevo modelo -->
                            <button type="button" class="btn btn-green-600 ms-2 m-auto" title="agregar modelo" id="addModeloBtn" disabled data-bs-toggle="modal" data-bs-target="#createModeloModal" style="height: 38px;">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <!-- Columna de imagen -->
                <div class="col-lg-5 d-flex align-items-center">
                    <img src="" alt="Imagen de la categoría" class="img-fluid">
                </div>
            </div>
        </div>


        <div class="card border border-dark p-3 my-3">
            <div class="card-header">Opciones de lista de CI</div>
            <hr>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="columnas" class="form-label">Seleccionar Columnas</label>
                    <select id="columnas" name="columnas[]" class="form-select" multiple>
                        <!-- Opciones predeterminadas que no se pueden deseleccionar -->
                        <option value="nombre" selected>Nombre</option>
                        <option value="codigo_de_inventario" selected>Código de Inventario</option>
                        <option value="numero_de_serie" selected>Número de Serie</option>
                        <!-- Opciones adicionales -->
                        <option value="departamento">Departamento</option>
                    </select>
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="numero_filas" class="form-label">Número de Filas</label>
                    <input type="number" id="numero_filas" name="numero_filas" class="form-control" min="1" value="1">
                </div>
                <div class="col-lg-12 mb-3">
                    <button type="button" id="generateBtn" class="btn btn-primary">Generar Lista</button>
                </div>
            </div>
        </div>

        <div class="card border border-dark p-3 my-3">
            <div class="card-header">Lista de CI
                <hr>
            </div>
            <div id="ciContainer" class="row">
                <!-- Aquí se agregarán las filas dinámicamente -->
            </div>
        </div>

        <!-- Botón de enviar -->
        <button type="submit" class="btn btn-primary">Guardar</button>
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
        initializeSelect2('#modelo_id');
        initializeSelect2('.js-select-ubicacion');
        initializeSelect2('#columnas');

        $(document).ready(function() {

            // Manejar cambio en el select de fabricantes
            $('#fabricante_id').on('change', function() {
                var fabricanteId = $(this).val();
                var fabricanteText = $(this).find('option:selected').text();

                $('#addModeloBtn').prop('disabled', !fabricanteId);
                $('#selectedFabricante').text(fabricanteText);
                $('#fabricante_id_modelo').val(fabricanteId);

                if (fabricanteId) {
                    // Obtener modelos por fabricante
                    $.ajax({
                        url: '/modelos/por-fabricante/' + fabricanteId,
                        method: 'GET',
                        success: function(response) {
                            var $modeloSelect = $('#modelo_id').empty();
                            response.modelos.forEach(function(modelo) {
                                $modeloSelect.append(new Option(modelo.nombre, modelo.id));
                            });
                        }
                    });
                } else {
                    $('#selectedFabricante').text('');
                    $('#fabricante_id_modelo').val('');
                    $('#modelo_id').empty().append('<option value="" disabled selected>Seleccione un modelo</option>');
                }
            });

            // Manejar la creación de fabricantes
            $('#createFabricanteForm').on('submit', function(e) {
                e.preventDefault();
                var nombre = $('#nuevo_fabricante_nombre').val();

                $.ajax({
                    url: '{{ route('fabricantes.store') }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        nombre: nombre
                    },
                    success: function(response) {
                        $('#createFabricanteModal').modal('hide');
                        $('.modal-backdrop').remove();
                        $('#nuevo_fabricante_nombre').val('');

                        // Añadir el nuevo fabricante al select
                        var newOption = new Option(response.nombre, response.id, true, true);
                        $('#fabricante_id').append(newOption).trigger('change');

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

            // Manejar la creación de modelos
            $('#createModeloForm').on('submit', function(e) {
                e.preventDefault();
                var nombre = $('#nuevo_modelo_nombre').val();
                var fabricanteId = $('#fabricante_id_modelo').val();

                $.ajax({
                    url: '{{ route('modelos.store') }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        nombre: nombre,
                        fabricante_id: fabricanteId
                    },
                    success: function(response) {
                        $('#createModeloModal').modal('hide');
                        $('.modal-backdrop').remove();
                        $('#nuevo_modelo_nombre').val('');

                        // Añadir el nuevo modelo al select de modelos
                        var newOption = new Option(response.nombre, response.id, true, true);
                        $('#modelo_id').append(newOption).trigger('change');

                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'Nuevo modelo creado con éxito.',
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
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
    });
</script>

<script>
    $(document).ready(function() {
        // Inicializar Select2 para el campo de ubicación

        $('#generateBtn').on('click', function() {
            var columnasSeleccionadas = $('#columnas').val();
            var numeroFilas = $('#numero_filas').val();

            // Validar entradas
            if (!numeroFilas || numeroFilas <= 0) {
                console.log('Número de filas no válido');
                return;
            }

            // Limpiar el contenedor antes de agregar nuevos elementos
            var $ciContainer = $('#ciContainer').empty();

            // Crear las filas dinámicamente
            for (var i = 0; i < numeroFilas; i++) {
                var filaNumero = (i + 1).toString().padStart(3, '0'); // Formatear número de fila
                var $row = $('<div class="row mb-3">');

                var columnWidth = Math.floor(12 / (columnasSeleccionadas.length + 1)); // Dividir el ancho de columna

                // Añadir el número de la fila
                var $label = $('<div class="col-lg-' + columnWidth + ' mb-3">' + '<label class="form-label">CI ' + filaNumero + '</label></div>');
                $row.append($label);

                // Añadir los campos de entrada
                columnasSeleccionadas.forEach(function(columna) {
                    if (columna === 'departamento') {
                        // Si la columna es "departamento", mostrar el campo de ubicación
                        var ubicacionHtml = '<div class="col-lg-' + columnWidth + ' mb-3">' +
                            '<label for="ubicacion_' + i + '" class="form-label">Ubicación</label>' +
                            '<select id="ubicacion_' + i + '" name="ubicacion[]" class="form-control js-select-ubicacion" data-show-subtext="true" data-live-search="true">' +
                            '@foreach($departamentos as $departamento)' +
                            '<option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>' +
                            '@endforeach' +
                            '</select>' +
                            '</div>';
                        $row.append(ubicacionHtml);
                    } else if (['nombre', 'codigo_de_inventario', 'numero_de_serie'].includes(columna)) {
                        // Mostrar los campos fijos "Nombre", "Código de Inventario" y "Número de Serie"
                        var labelText = columna.replace(/_/g, ' ').toUpperCase();
                        var inputHtml = '<div class="col-lg-' + columnWidth + ' mb-3">' +
                            '<label for="' + columna + '_' + i + '" class="form-label">' + labelText + '</label>' +
                            '<input type="text" class="form-control" id="' + columna + '_' + i + '" name="' + columna + '[]" required>' +
                            '</div>';
                        $row.append(inputHtml);
                    }
                });

                $ciContainer.append($row);
            }

            // Inicializar Select2 para los nuevos campos de ubicación
            $('.js-select-ubicacion').select2();
        });

        $('#columnas').on('change', function() {
            var columnasSeleccionadas = $(this).val();
            if (columnasSeleccionadas.includes('departamento')) {
                $('#ubicacionContainer').show();
            } else {
                $('#ubicacionContainer').hide();
            }
        });

        // Inicializar el select con las opciones predeterminadas
        $('#columnas').select2({
            placeholder: 'Seleccionar columnas',
            allowClear: false
        });

        // Inicializar el contenedor de ubicación (por defecto oculto)
        $('#ubicacionContainer').hide();
    });
</script>

@endsection