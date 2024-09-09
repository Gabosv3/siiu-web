@extends('layouts.user_type.auth')

@section('content')
<div class="container mt-5">
        <!-- Formulario de carga de CSV -->
        <form action="{{ route('csv.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Subir CSV</label>
                <input type="file" name="file" id="file" class="form-control" accept=".csv">
            </div>
            <button type="submit" class="btn btn-primary">Subir y Cargar Datos</button>
        </form>

        <!-- Formulario de configuración de la lista -->
        <div class="card border border-dark p-3 my-3">
            <div class="card-header">Opciones de Lista de CI</div>
            <hr>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="columnas" class="form-label">Seleccionar Columnas</label>
                    <select id="columnas" name="columnas[]" class="form-select" multiple>
                        <option value="nombre" selected>Nombre</option>
                        <option value="codigo_de_inventario" selected>Código de Inventario</option>
                        <option value="numero_de_serie" selected>Número de Serie</option>
                        <option value="ubicacion">Ubicación</option>
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

        <!-- Contenedor para mostrar los datos de la lista -->
        <div class="card border border-dark p-3 my-3">
            <div class="card-header">Lista de CI</div>
            <hr>
            <div id="ciContainer" class="row">
                <!-- Aquí se agregarán las filas dinámicamente -->
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Función para inicializar Select2
        function initializeSelect2(selector) {
            $(selector).select2({
                placeholder: "Seleccione una opción",
                theme: "bootstrap-5",
                width: '100%',
            });
        }

        // Inicializar selectores Select2
        initializeSelect2('#fabricante_id');
        initializeSelect2('#modelo_id');
        initializeSelect2('.js-select-ubicacion');
        initializeSelect2('#columnas');

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

        // Parte relacionada con la generación de filas dinámicas
        var csvData = @json($csv_data['data'] ?? []);

        $('#generateBtn').on('click', function() {
            var columnasSeleccionadas = $('#columnas').val();
            var numeroFilas = $('#numero_filas').val();

            // Validar entradas
            if (!columnasSeleccionadas || numeroFilas <= 0) {
                console.log('Columnas o número de filas no válidos');
                return;
            }

            // Limpiar el contenedor antes de agregar nuevos elementos
            var $ciContainer = $('#ciContainer').empty();

            // Crear las filas dinámicamente
            for (var i = 0; i < numeroFilas; i++) {
                var filaNumero = (i + 1).toString().padStart(3, '0'); // Formatear número de fila
                var $row = $('<div class="row mb-3">');
                
                var columnWidth = Math.floor((12 - 1) / columnasSeleccionadas.length); // Restar 1 para la columna de número de fila
                // Añadir el número de la fila
                var $label = $('<div class="col-lg-' + columnWidth + ' mb-3">' + '<label class="form-label">CI ' + filaNumero + '</label></div>');
                $row.append($label);

                // Añadir los campos de entrada
                columnasSeleccionadas.forEach(function(columna) {
                    var labelText = columna.replace(/_/g, ' ').toUpperCase();
                    var value = (csvData[i] && csvData[i][columna]) ? csvData[i][columna] : '';
                    var inputHtml = '<div class="col-lg-' + columnWidth + ' mb-3">' +
                                    '<label for="' + columna + '_' + i + '" class="form-label">' + labelText + '</label>' +
                                    '<input type="text" class="form-control" id="' + columna + '_' + i + '" name="' + columna + '[]" value="' + value + '" required>' +
                                    '</div>';
                    $row.append(inputHtml);
                });

                $ciContainer.append($row);
            }
        });

        // Mostrar u ocultar el contenedor de ubicación según las columnas seleccionadas
        $('#columnas').on('change', function() {
            var columnasSeleccionadas = $(this).val();
            if (columnasSeleccionadas.includes('departamento')) {
                $('#ubicacionContainer').show();
            } else {
                $('#ubicacionContainer').hide();
            }
        });

        // Inicializar el select de columnas con opciones predeterminadas
        $('#columnas').select2({
            placeholder: 'Seleccionar columnas',
            allowClear: false
        });

        // Ocultar el contenedor de ubicación al cargar la página
        $('#ubicacionContainer').hide();
    });
</script>
@endsection

