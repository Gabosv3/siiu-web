@extends('layouts.user_type.auth')

@section('content')
<!-- Formulario principal -->
<div class="p-3">
    <h3>Crear Activo</h3>
    <div class="card border border-dark p-3 my-3">
        <!-- Formulario de carga de CSV -->
        <form id="csvUploadForm" action="{{ route('csv.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Contenedor para el archivo CSV -->
            <div class="mb-3">
                <label for="csv_file" class="form-label">Selecciona el archivo CSV</label>
                <input type="file" id="csv_file" name="csv_file" accept=".csv" class="form-control" required>
            </div>

            <!-- Botón de carga -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Subir CSV</button>
            </div>
        </form>
    </div>

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
                    <input type="hidden" name="category_id" value="{{ $categoria->id }}">
                    <!-- Estado -->
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select id="estado" name="status" class="form-control" required>
                            <option value="nuevo">Nuevo</option>
                            <option value="asignado">Asignado</option>
                        </select>
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

                <!-- Columna de imagen -->
                <div class="col-lg-5 d-flex align-items-center justify-content-center">
                    <img src="{{ asset( $categoria->image) }}" alt="Categoría" class="img-fluid rounded shadow" style="max-width: 300px; height: auto; border: 2px solid #ccc; padding: 10px;">
                </div>
            </div>
        </div>
        <div class="card border border-dark p-3 my-3">
            <div class="card-header">Garantia</div>
            <hr>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="garantia" class="form-label">Garantía</label>
                    <input type="number" id="garantia" name="warranty" class="form-control" min="0" value="0" required>
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="garantia_unidad" class="form-label">Unidad</label>
                    <select id="garantia_unidad" name="warranty_unit" class="form-control" required>
                        <option value="meses">Meses</option>
                        <option value="anios">Años</option>
                    </select>
                </div>
                <div class="col-lg-12 mb-3">
                    <label for="fecha_garantia" class="form-label">Fecha de Vencimiento de Garantía</label>
                    <input type="text" id="fecha_garantia" class="form-control" readonly>
                    <input type="hidden" name="warranty_expiration_date" id="warranty_expiration_date">
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
                        <option value="name" selected>Nombre</option>
                        <option value="inventory_code" selected>Código de Inventario</option>
                        <option value="serial_number" selected>Número de Serie</option>
                        <!-- Opciones adicionales -->
                        <option value="location_id">Departamento</option>
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



<script>
    $(document).ready(function() {

        const translations = {
            'name': 'Nombre',
            'inventory_code': 'Código de Inventario',
            'serial_number': 'Número de Serie'
        };

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

        // Maneja csv upload
        $('#csvUploadForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Limpiar el contenedor actual
                    $('#ciContainer').empty();

                    // Procesar los datos CSV y agregar filas al contenedor
                    if (response.data && response.data.length > 0) {
                        // Obtener los nombres de las columnas de la primera fila
                        let columnNames = response.data[0];
                        // Definir las columnas permitidas
                        let allowedColumns = ['name', 'inventory_code', 'serial_number'];

                        // Filtrar las columnas permitidas
                        let filteredColumnNames = columnNames.filter(column => allowedColumns.includes(column));

                        // Verificar si hay columnas permitidas
                        if (filteredColumnNames.length > 0) {
                            // Omite la primera fila de datos (que contiene los nombres de las columnas)
                            response.data.slice(1).forEach(function(row, index) {
                                let filaNumero = (index + 1).toString().padStart(3, '0');
                                let $row = $('<div class="row mb-3">');
                                let columnWidth = Math.floor(12 / (filteredColumnNames.length + 1)); // Ajusta el ancho de las columnas

                                let $label = $('<div class="col-lg-' + columnWidth + ' mb-3"><label class="form-label">CI ' + filaNumero + '</label></div>');
                                $row.append($label);

                                filteredColumnNames.forEach(function(columnName, colIndex) {
                                    let value = row[columnNames.indexOf(columnName)]; // Obtén el valor basado en el índice original
                                    let inputHtml = '<div class="col-lg-' + columnWidth + ' mb-3">' +
                                        '<label for="' + columnName + '_' + index + '" class="form-label">' + (translations[columnName] || columnName.replace(/_/g, ' ').toUpperCase()) + '</label>' +
                                        '<input type="text" class="form-control" id="' + columnName + '_' + index + '" name="' + columnName + '[]" value="' + value + '" required>' +
                                        '</div>';
                                    $row.append(inputHtml);
                                });

                                $('#ciContainer').append($row);
                            });

                            Swal.fire({
                                title: '¡Éxito!',
                                text: 'Archivo CSV cargado y datos actualizados.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                        } else {
                            Swal.fire({
                                title: 'Información',
                                text: 'El archivo CSV no contiene las columnas requeridas.',
                                icon: 'info',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    } else {
                        Swal.fire({
                            title: 'Información',
                            text: 'No se encontraron datos en el archivo CSV.',
                            icon: 'info',
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


        // Manejo del cambio en el select de fabricante
        $('#fabricante_id').on('change', function() {
            let fabricanteId = $(this).val();
            let fabricanteText = $(this).find('option:selected').text();

            $('#addModeloBtn').prop('disabled', !fabricanteId);
            $('#selectedFabricante').text(fabricanteText);
            $('#fabricante_id_modelo').val(fabricanteId);

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
                $('#selectedFabricante').text('');
                $('#fabricante_id_modelo').val('');
                $('#modelo_id').empty().append('<option value="" disabled selected>Seleccione un modelo</option>');
            }
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
                    nombre: nombre
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
                    $('#createModeloModal').modal('hide');
                    $('#nuevo_modelo_nombre').val('');
                    let newOption = new Option(response.nombre, response.id, true, true);
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

        // Manejo del botón para generar filas dinámicas
        $('#generateBtn').on('click', function() {
            let columnasSeleccionadas = $('#columnas').val();
            let numeroFilas = $('#numero_filas').val();

            if (!numeroFilas || numeroFilas <= 0) {
                console.log('Número de filas no válido');
                return;
            }

            let $ciContainer = $('#ciContainer').empty();

            for (let i = 0; i < numeroFilas; i++) {
                let filaNumero = (i + 1).toString().padStart(3, '0');
                let $row = $('<div class="row mb-3">');
                let columnWidth = Math.floor(12 / (columnasSeleccionadas.length + 1));

                let $label = $('<div class="col-lg-' + columnWidth + ' mb-3"><label class="form-label">CI ' + filaNumero + '</label></div>');
                $row.append($label);

                columnasSeleccionadas.forEach(function(columna) {
                    if (columna === 'departament_id') {
                        let ubicacionHtml = '<div class="col-lg-' + columnWidth + ' mb-3">' +
                            '<label for="ubicacion_' + i + '" class="form-label">Ubicación</label>' +
                            '<select id="ubicacion_' + i + '" name="location_id[]" class="form-control js-select-ubicacion">' +
                            '@foreach($departamentos as $departamento)' +
                            '<option value="{{ $departamento->id }}">{{ $departamento->name }}</option>' +
                            '@endforeach' +
                            '</select></div>';
                        $row.append(ubicacionHtml);
                    } else if (['name', 'inventory_code', 'serial_number'].includes(columna)) {
                        let cambiarIdioma = {
                            'name': 'nombre',
                            'inventory_code': 'codigo_de_inventario',
                            'serial_number': 'numero_de_serie'
                        };
                        let labelText = cambiarIdioma[columna].replace(/_/g, ' ').toUpperCase();
                        let inputHtml = '<div class="col-lg-' + columnWidth + ' mb-3">' +
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

        // Manejo del cambio en el select de columnas
        $('#columnas').on('change', function() {
            let columnasSeleccionadas = $(this).val();

            if (columnasSeleccionadas.includes('ubicacion')) {
                $('#ubicacionFields').removeClass('d-none');
            } else {
                $('#ubicacionFields').addClass('d-none');
            }
        });

    });
</script>

<script>
    const garantiaInput = document.getElementById('garantia');
    const garantiaUnidadSelect = document.getElementById('garantia_unidad');
    const fechaGarantiaInput = document.getElementById('fecha_garantia');
    const warrantyExpirationDateInput = document.getElementById('warranty_expiration_date');

    function calcularFechaGarantia() {
        const garantia = parseInt(garantiaInput.value);
        const unidad = garantiaUnidadSelect.value;

        if (garantia > 0) {
            const fechaActual = new Date();

            if (unidad === 'meses') {
                fechaActual.setMonth(fechaActual.getMonth() + garantia);
            } else if (unidad === 'anios') {
                fechaActual.setFullYear(fechaActual.getFullYear() + garantia);
            }

            // Formatear la fecha resultante
            const dia = String(fechaActual.getDate()).padStart(2, '0');
            const mes = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Los meses son de 0 a 11
            const anio = fechaActual.getFullYear();

            fechaGarantiaInput.value = `${dia}/${mes}/${anio}`;
            warrantyExpirationDateInput.value = `${anio}-${mes}-${dia}`;
        } else {
            fechaGarantiaInput.value = '';
            warrantyExpirationDateInput.value = '';
        }
    }

    // Escuchar cambios en los inputs
    garantiaInput.addEventListener('input', calcularFechaGarantia);
    garantiaUnidadSelect.addEventListener('change', calcularFechaGarantia);

    // Calcular fecha al cargar
    calcularFechaGarantia();
</script>
@endsection