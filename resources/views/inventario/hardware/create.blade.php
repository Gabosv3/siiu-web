@extends('layouts.user_type.auth')

@section('content')
<!-- Formulario principal -->
<div class="p-3">
    <h3>Crear Activo</h3>

    <!-- Formulario de carga de CSV -->
    <form id="csvUploadForm" action="{{ route('csv.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv" required>
        <button type="submit" class="btn btn-primary">Subir CSV</button>
    </form>

    <!-- Formulario para crear hardware -->
    <form id="hardwareForm" action="{{ route('hardwares.store') }}" method="POST">
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
                <div class="col-lg-5 d-flex align-items-center justify-content-center">
                    <img src="{{ asset( $categoria->imagen) }}" alt="Imagen de la categoría" class="img-fluid rounded shadow" style="max-width: 300px; height: auto; border: 2px solid #ccc; padding: 10px;">
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

<!-- Incluir jQuery y Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
        initializeSelect2('#modelo_id');
        initializeSelect2('.js-select-ubicacion');
        initializeSelect2('#columnas');

        $('#csvUploadForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.data) {
                    var $table = $('<table class="table table-striped"><thead><tr></tr></thead><tbody></tbody></table>');

                    // Crear encabezados
                    var $thead = $table.find('thead tr');
                    if (response.data.length > 0) {
                        Object.keys(response.data[0]).forEach(function(key) {
                            $thead.append('<th>' + key + '</th>');
                        });

                        // Crear filas de datos
                        var $tbody = $table.find('tbody');
                        response.data.forEach(function(row) {
                            var $tr = $('<tr></tr>');
                            Object.values(row).forEach(function(value) {
                                $tr.append('<td>' + value + '</td>');
                            });
                            $tbody.append($tr);
                        });
                    } else {
                        $thead.append('<th>No data found</th>');
                    }

                    // Agregar la tabla al contenedor
                    $('#tableContainer').empty().append($table);

                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Archivo CSV cargado y datos actualizados.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un error al procesar el archivo CSV.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

        $('#fabricante_id').on('change', function() {
            var fabricanteId = $(this).val();
            var fabricanteText = $(this).find('option:selected').text();

            $('#addModeloBtn').prop('disabled', !fabricanteId);
            $('#selectedFabricante').text(fabricanteText);
            $('#fabricante_id_modelo').val(fabricanteId);

            if (fabricanteId) {
                $.ajax({
                    url: '/modelos/por-fabricante/' + fabricanteId,
                    method: 'GET',
                    success: function(response) {
                        var $modeloSelect = $('#modelo_id').empty();
                        response.modelos.forEach(function(modelo) {
                            $modeloSelect.append(new Option(modelo.nombre, modelo.id));
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
                $('#selectedFabricante').text('');
                $('#fabricante_id_modelo').val('');
                $('#modelo_id').empty().append('<option value="" disabled selected>Seleccione un modelo</option>');
            }
        });

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
                    console.log(response); // Agrega esta línea para verificar los datos de la respuesta
                    var columnas = response.columns;
                    $('#columnas').empty().append('<option value="" disabled>Seleccionar columnas</option>');
                    columnas.forEach(function(columna) {
                        $('#columnas').append('<option value="' + columna + '">' + columna + '</option>');
                    });
                    $('#columnas').select2();
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Archivo CSV cargado y columnas actualizadas.',
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
                    $('#nuevo_modelo_nombre').val('');
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

        $('#generateBtn').on('click', function() {
            var columnasSeleccionadas = $('#columnas').val();
            var numeroFilas = $('#numero_filas').val();

            if (!numeroFilas || numeroFilas <= 0) {
                console.log('Número de filas no válido');
                return;
            }

            var $ciContainer = $('#ciContainer').empty();

            for (var i = 0; i < numeroFilas; i++) {
                var filaNumero = (i + 1).toString().padStart(3, '0');
                var $row = $('<div class="row mb-3">');
                var columnWidth = Math.floor(12 / (columnasSeleccionadas.length + 1));

                var $label = $('<div class="col-lg-' + columnWidth + ' mb-3"><label class="form-label">CI ' + filaNumero + '</label></div>');
                $row.append($label);

                columnasSeleccionadas.forEach(function(columna) {
                    if (columna === 'departamento') {
                        var ubicacionHtml = '<div class="col-lg-' + columnWidth + ' mb-3">' +
                            '<label for="ubicacion_' + i + '" class="form-label">Ubicación</label>' +
                            '<select id="ubicacion_' + i + '" name="ubicacion[]" class="form-control js-select-ubicacion">' +
                            '@foreach($departamentos as $departamento)' +
                            '<option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>' +
                            '@endforeach' +
                            '</select></div>';
                        $row.append(ubicacionHtml);
                    } else if (['nombre', 'codigo_de_inventario', 'numero_de_serie'].includes(columna)) {
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

        $('#columnas').select2({
            placeholder: 'Seleccionar columnas',
            allowClear: false
        });

        $('#ubicacionContainer').hide();
    });
</script>

@endsection