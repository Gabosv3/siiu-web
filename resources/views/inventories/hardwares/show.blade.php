@extends('layouts.user_type.auth')

@section('content')
<!-- Tabs Navigation -->
<div class=" p-3" style="min-height: 80vh;">
    <div class="card p-3" style="width: 100%;">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-5 g-3 align-items-stretch">
            <!-- Imagen de la categoría -->
            <div class="col d-flex align-items-center justify-content-center">
                <img src="{{ $hardware->category->image }}" class="img-fluid rounded-start" alt="Imagen de la categoría"
                    style="max-width: 200px;">
            </div>
            <!-- Información principal -->
            <div class="col">
                <div class="card-body text-center text-md-start">
                    <h3 class="card-title text-uppercase">{{ $hardware->name }}</h3>
                    <p class="text-muted">{{ $hardware->inventory_code }}</p>
                    <small class="text-muted">Actualizado {{ $hardware->updated_at->format('M d Y, H:i') }}</small>
                    <p class="text-muted pt-2">
                        <strong>Último Mantenimiento:</strong> <br>
                        @if($hardware->last_maintenance_at)
                        @if($hardware->last_maintenance_at->lt(now()->subMonths(3)))
                        Necesita mantenimiento
                        @else
                        {{ $hardware->last_maintenance_at->format('M d Y, H:i') }}
                        @endif
                        @else
                        No se ha realizado mantenimiento nunca
                        @endif
                    </p>
                </div>
            </div>
            <!-- Asignación -->
            <div class="col d-flex">
                <div class="card p-3 text-center flex-grow-1 d-flex flex-column align-items-center justify-content-center"
                    style="background-color: #ECECEC;">
                    <i class="fa fa-user fa-2x text-secondary mb-2"></i>
                    <p class="m-0 text-uppercase text-secondary fw-bold">Asignado a</p>
                    <p class="m-0 text-uppercase text-secondary">
                        {{ $hardware->hardwareAssigned->user->name ?? 'No asignado' }}
                    </p>
                    <button class="btn btn-green-600 mt-2" title="Editar" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        <i class="fa fa-edit"></i>
                    </button>
                </div>
            </div>
            <!-- Ubicación -->
            <div class="col d-flex">
                <div class="card p-3 text-center flex-grow-1 d-flex flex-column align-items-center justify-content-center"
                    style="background-color: #ECECEC;">
                    <i class="fa fa-map fa-2x text-secondary mb-2"></i>
                    <p class="m-0 text-uppercase text-secondary fw-bold">Ubicación en</p>
                    <p class="m-0 text-secondary">
                        {{ $hardware->hardwareAssigned->departament->name ?? 'No Ubicado' }}
                    </p>
                </div>
            </div>
            <!-- Estado -->
            <div class="col d-flex">
                <div class="card p-3 text-center flex-grow-1 d-flex flex-column align-items-center justify-content-center"
                    style="background-color: #ECECEC;">
                    <i class="fa fa-user fa-2x text-secondary mb-2"></i>
                    <p class="m-0 text-uppercase text-secondary fw-bold">Estado</p>
                    <p class="m-0 text-secondary text-capitalize">{{ $hardware->status }}</p>
                </div>
            </div>
        </div>

        <!-- Navegación y código de barras -->
        <div class="row align-items-center">
            <!-- Tabs -->
            <div class="col-12 col-lg-6">
                <ul class="nav nav-tabs justify-content-center justify-content-lg-start" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="company-tab" data-bs-toggle="tab"
                            data-bs-target="#company-tab-pane" type="button" role="tab"
                            aria-controls="company-tab-pane" aria-selected="true">EQUIPO</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="values-tab" data-bs-toggle="tab" data-bs-target="#values-tab-pane"
                            type="button" role="tab" aria-controls="values-tab-pane"
                            aria-selected="false">LICENCIAS</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="culture-tab" data-bs-toggle="tab"
                            data-bs-target="#culture-tab-pane" type="button" role="tab"
                            aria-controls="culture-tab-pane" aria-selected="false">DOCUMENTOS</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="philosophy-tab" data-bs-toggle="tab"
                            data-bs-target="#philosophy-tab-pane" type="button" role="tab"
                            aria-controls="philosophy-tab-pane" aria-selected="false">HISTORIAL</button>
                    </li>
                </ul>
            </div>
            <!-- Código de barras -->
            <div class="col-12 col-lg-6 text-center text-lg-end mt-3 mt-lg-0">
                <div class="d-flex justify-content-around">
                    <h5>Código de Barras</h5>
                    <div id="barcodeDiv" class="mb-3 text-center">
                        <img src="{{ asset('storage/' . $hardware->barcode_path) }}" class="img-fluid"
                            alt="Código de Barras" style="max-width: 200px;">
                        <p class="m-0">{{ $hardware->inventory_code }}</p>
                    </div>
                    <button onclick="printDiv('barcodeDiv')" class="btn btn-primary"><i class="fa fa-print"></i>
                        Imprimir</button>
                </div>
            </div>
        </div>
    </div>



    <div class="tab-content  p-3" id="myTabContent">
        <!-- Company Tab Content -->
        <div class="tab-pane fade show active" id="company-tab-pane" role="tabpanel" aria-labelledby="company-tab"
            tabindex="0">
            <div class="row ">
                <div class="col-6">
                    <div class="card p-3 border"
                        style="background-color: white; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                        <h2>Detalles</h2>
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-2 g-lg-3">
                            <div class="col">
                                <div class="p-3">
                                    <h5>Numero de serie</h5>
                                    <p>{{ $hardware->serial_number }}</p>
                                </div>
                            </div>
                            @foreach ($hardware->model->characteristics as $characteristic)
                            <div class="col">
                                <div class="p-3">
                                    <h5>{{ $characteristic->name }}</h5>
                                    <p>{{ $characteristic->pivot->value }}</p>
                                    <!-- Muestra el valor de la característica -->
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>


                <div class="col-6">
                    <div class="card p-3 border"
                        style="background-color: white; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                        <h2>Garantia</h2>
                        @if ($hardware->warranty_expiration_date > date('Y-m-d'))
                        <div class="row row-cols-1 row-cols-lg-1 g-2 g-lg-3">
                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Garantia</h5>
                                    <p>{{ $hardware->warranty_expiration_date }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Modelo</h5>
                                    <p>{{ $hardware->model->name }}</p>
                                </div>
                            </div>
                        </div>
                        @else
                        <h2>No tiene garantia</h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab de licencias -->
        <div class="tab-pane fade card p-5 " id="values-tab-pane" role="tabpanel" aria-labelledby="values-tab"
            tabindex="0">
            <h1>Licencias Vinculadas</h1>

            <!-- Botón para abrir el modal -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#linkLicenseModal">
                Vincular Licencia
            </button>

            <!-- Tabla de licencias -->
            <table class="datatable2 w-100 responsive">
                <thead>
                    <tr>
                        <th>Software</th>
                        <th>Version de Software</th>
                        <th>Clave de Licencia</th>
                        <th>Fecha de Expiración</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($linkedLicenses as $link)
                    <tr>
                        <td>{{ $link->software->software_name }}</td>
                        <td>{{ $link->software->version }}</td>
                        <td>{{ $link->license_key ?? 'No asignada' }}</td>
                        <td>{{ $link->expiration_date ?? 'N/A' }}</td>
                        <td>
                            {{ $link->status ?? 'N/A' }}
                            @if ($link->license && $link->license->expiration_date < now())
                                <span class="text-danger">(Expirada)</span>
                                @endif
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <!-- Tab de Documentos -->
        <div class="tab-pane fade card p-5" id="culture-tab-pane" role="tabpanel" aria-labelledby="culture-tab"
            tabindex="0">

            <h1>Documentos</h1>
            <!-- Botón de Crear Nuevo Archivo -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFileModal">
                Crear Nuevo Archivo
            </button>

            <!-- Tabla de Archivos -->
            <table class="table datatable w-100 responsive">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                        <th>Ubicación</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($hardwareFiles as $file)
                    <tr>
                        <td>{{ $file->name }}</td>
                        <td>{{ $file->description }}</td>
                        @if ($file->trashed())
                        <!-- Si está eliminado (SoftDelete activado), mostrar solo Restaurar -->
                        <td>
                            <form action="{{ route('hardware.file.restore', $file->id) }}" method="POST" class="formulario-restaurar">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-cyan-800 btn-sm">Restaurar</button>
                            </form>
                        </td>
                        @else
                        <td>
                            <!-- Si NO está eliminado, mostrar las acciones normales -->
                            <form action="{{ route('hardware.file.destroy', $file->id) }}" method="POST" class="formulario-eliminar">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-red-800 btn-sm">Eliminar</button>
                            </form>

                        </td>
                        <td>
                            <button type="button" class="btn btn-cyan-800 btn-sm" data-toggle="modal"
                                data-target="#pdfModal{{ $file->id }}">
                                Previsualizar PDF
                            </button>
                            <a href="{{ route('download.file', $file->id) }}" class="btn btn-green-600 btn-sm">Descargar</a>
                        </td>

                        @endif
                    </tr>

                    <!-- Modal para previsualizar PDF -->
                    <div class="modal fade" id="pdfModal{{ $file->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="pdfModalLabel{{ $file->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="pdfModalLabel{{ $file->id }}">
                                        Previsualización de PDF
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <iframe src="{{ Storage::url($file->location) }}" width="100%" height="500px"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>

            </table>
        </div>

        <!-- Philosophy Tab Content -->
        <div class="tab-pane fade" id="philosophy-tab-pane" role="tabpanel" aria-labelledby="philosophy-tab"
            tabindex="0">
            <h1>Historial</h1>
            <div class="">

                <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3">
                    @foreach ($histories as $history)
                    <div class="col" data-toggle="modal" data-target="#historyModal{{ $history->id }}">
                        <div class="p-3 border bg-white" style="cursor: pointer;">
                            {{ $history->action }}
                        </div>
                    </div>

                    <!-- Modal para mostrar más información -->
                    <div class="modal fade" id="historyModal{{ $history->id }}" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel{{ $history->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="historyModalLabel{{ $history->id }}">Detalles de la acción</h5>
                                    <button type="button " class="close btn btn-red-800" data-dismiss="modal" aria-label="Cerrar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <strong>Acción:</strong> {{ $history->action }}<br>
                                    <strong>Descripción:</strong> {{ $history->description }}<br>
                                    <strong>Fecha:</strong> {{ $history->created_at }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>





    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Asignar Equipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="assignmentForm">
                    @csrf
                    <div class="modal-body">
                        <!-- Selección de usuarios -->
                        <div class="mb-3">
                            <label for="user_ids" class="form-label">Encargados:</label>
                            <select class="form-control" id="user_ids" name="user_id" >
                                <option value="">Sin encargado</option>
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}, {{ $user->email }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Selección de departamento -->
                        <div class="mb-3">
                            <label for="departament_id" class="form-label">Departamento:</label>
                            <select class="form-control" id="departament_id" name="departament_id">
                                <option value="">Sin departamento</option>
                                @foreach ($departaments as $departament)
                                <option value="{{ $departament->id }}">{{ $departament->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ID del hardware -->
                        <input type="hidden" name="hardware_id" id="hardware_id" value="{{ $hardware->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" id="saveChangesButton">Guardar
                            cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para vincular licencias -->
    <div class="modal fade" id="linkLicenseModal" tabindex="-1" aria-labelledby="linkLicenseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="linkLicenseForm" method="POST" action="{{ route('license.link') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="linkLicenseModalLabel">Vincular Licencia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- ID del equipo (campo oculto) -->
                        <input type="hidden" name="equipment_id" id="equipment_id" value="{{ $hardware->id }}">

                        <!-- Selección de Software -->
                        <div class="mb-3">
                            <label for="softwareSelect" class="form-label">Seleccionar Software</label>
                            <select id="softwareSelect" class="form-select" name="software_id" required>
                                <option value="">Seleciona</option>
                                <optgroup label="Gratis">
                                    @foreach ($freeSoftwares as $software)
                                    <option value="{{ $software->id }}">{{ $software->software_name }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="De Pago">
                                    @foreach ($paidSoftwares as $software)
                                    <option value="{{ $software->id }}">{{ $software->software_name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        <!-- Selección de Licencia (solo si es de pago) -->
                        <div class="mb-3" id="licenseSection" style="display: none;">
                            <label for="licenseSelect" class="form-label">Seleccionar Licencia</label>
                            <select id="licenseSelect" class="form-select" name="license_id">
                                @foreach ($availableLicenses as $license)
                                <option value="{{ $license->id }}">
                                    {{ $license->license_key }} - Disponibles: {{ $license->max_devices - $license->used_devices }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Vincular</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="createFileModal" tabindex="-1" aria-labelledby="createFileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFileModalLabel">Crear Nuevo Archivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="createFileForm" enctype="multipart/form-data">
                        @csrf
                        <!-- Campo oculto para hardware_id -->
                        <input type="hidden" id="hardware_id" name="hardware_id" value="{{ $hardware->id }}">

                        <div class="mb-3">
                            <label for="file-name" class="form-label">Nombre del Archivo</label>
                            <input type="text" class="form-control" id="file-name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="file-description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="file-description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="file-upload" class="form-label">Subir Archivo</label>
                            <input type="file" class="form-control" id="file-upload" name="file-upload" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script>
        document.getElementById('softwareSelect').addEventListener('change', function() {
            var selectedSoftware = this.options[this.selectedIndex];
            var licenseSection = document.getElementById('licenseSection');

            if (selectedSoftware.closest('optgroup').label === 'De Pago') {
                licenseSection.style.display = 'block';
            } else {
                licenseSection.style.display = 'none';
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Inicializa Select2 para los campos select
            function initializeSelect2(selector) {
                $(selector).select2({
                    dropdownParent: $('#exampleModal'),
                    placeholder: "Seleccione una opción",
                    theme: "bootstrap-5",
                    width: '100%',
                });
            }

            // Configuración de Select2 al abrir el modal
            $('#exampleModal').on('shown.bs.modal', function() {
                initializeSelect2('#user_ids'); // Múltiples usuarios
                initializeSelect2('#departament_id'); // Departamento
            });

            // AJAX para guardar los cambios
            $('#assignmentForm').on('submit', function(e) {
                e.preventDefault();

                const userIds = $('#user_ids').val();
                const departmentId = $('#departament_id').val();
                const equipmentId = $('#hardware_id').val();

                // Llamada AJAX para asignar equipo
                $.ajax({
                    url: "{{ route('assign.equipment') }}",
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        user_id: userIds,
                        departament_id: departmentId,
                        hardware_id: equipmentId,
                    },
                    success: function(response) {
                        // Mensaje de éxito
                        Swal.fire({
                            title: '¡Éxito!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            location.reload(); // Recargar después de la alerta
                        });

                        // Cierra el modal
                        $('#exampleModal').modal('hide');

                        // Limpia los campos de selección
                        $('#user_ids').val(null).trigger('change');
                        $('#departament_id').val(null).trigger('change');

                        // Actualiza la visualización de datos
                        $('#assignationText').text(response.assigned_to || 'Sin asignar');
                        $('#departamentText').text(response.department_name ||
                            'Sin departamento');
                        $('#statusText').text(response.status || 'Disponible');
                    },
                    error: function(xhr) {
                        console.error(xhr); // Log para depuración

                        let errorMessage = 'Error al asignar el equipo.';

                        // Manejo de errores de validación
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = '';
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errors += value +
                                    "\n"; // Concatenar los mensajes de error
                            });
                            errorMessage = errors;
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message; // Error genérico
                        }

                        Swal.fire({
                            title: 'Error',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });
        });

        // Función para imprimir contenido (ejemplo: etiquetas)
        function printDiv(divId) {
            var contenido = document.getElementById(divId).innerHTML;
            var ventana = window.open('',);

            ventana.document.write(`
                <style>
                    /* Configuración para impresión de ticket de 40x30mm */
                    @page {
                        size: 40mm 30mm;
                        margin: 0;
                    }
                    body {
                        width: 40mm;
                        height: 30mm;
                        margin: 0;
                        font-family: Arial, sans-serif;
                    }
                    .print-content {
                        text-align: center;
                        padding: 5px;
                    }
                    img {
                        max-width: 100%;
                        height: auto;
                    }
                    h5, p {
                        margin: 0;
                        font-size: 10px;
                    }
                    .sistemas-info {
                        font-size: 8px;
                        margin-top: 5px;
                    }
                </style>
            `);
            ventana.document.write('</head><body>');
            ventana.document.write('<div class="print-content">' + contenido + '</div>');
            ventana.document.write('</body></html>');
            ventana.document.close();
            ventana.print();
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#createFileForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: "{{ route('upload-file') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Guardar la pestaña activa antes de recargar la página
                        localStorage.setItem('activeTab', '#culture-tab-pane');

                        // Mostrar alerta de éxito antes de recargar
                        Swal.fire({
                            title: '¡Éxito!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        }).then(() => {
                            location.reload(); // Recargar después de la alerta
                        });

                        // Cerrar modal y limpiar formulario
                        $('#createFileModal').modal('hide');
                        $('#createFileForm')[0].reset();
                    },
                    error: function(xhr) {
                        console.error(xhr);

                        let errorMessage = 'Hubo un error al subir el archivo.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = '';
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errors += value + "\n";
                            });
                            errorMessage = errors;
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        // Mostrar alerta de error
                        Swal.fire({
                            title: 'Error',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            });

            // Mantener la pestaña activa después de la recarga
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('button[data-bs-target="' + activeTab + '"]').tab('show');
                localStorage.removeItem('activeTab'); // Limpiar después de aplicarlo
            }
        });


        $(document).ready(function() {
            // Verifica que los elementos están siendo seleccionados
            console.log("Iniciando DataTables");

            // Selecciona todas las tablas con las clases .datatable y .datatable2
            $('.datatable, .datatable2').each(function() {
                console.log("Inicializando DataTable para una tabla"); // Verifica que se recorre cada tabla

                const table = $(this); // Obtén la tabla actual

                // Inicializa DataTables en la tabla actual
                table.DataTable({
                    responsive: true,
                    language: {
                        sProcessing: "Procesando...",
                        sLengthMenu: "Mostrar _MENU_ registros",
                        sZeroRecords: "No se encontraron resultados",
                        sEmptyTable: "Ningún dato disponible en esta tabla",
                        sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                        sSearch: "Buscar:",
                        sInfoThousands: ",",
                        sLoadingRecords: "Cargando...",
                        oPaginate: {
                            sFirst: '<i class="fas fa-angle-double-left"></i>',
                            sLast: '<i class="fas fa-angle-double-right"></i>',
                            sNext: '<i class="fas fa-angle-right"></i>',
                            sPrevious: '<i class="fas fa-angle-left"></i>'
                        },
                        oAria: {
                            sSortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sSortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });
        });
    </script>


    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Muestra un SweetAlert con un mensaje de error si hay errores
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ $errors->first() }}'
            }).then(() => {
                // Después de cerrar el SweetAlert, abre automáticamente el modal
                var modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
                modal.show();
            });
        });
    </script>
    @endif

    @include('components.script-btn')

    <script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>
    @endsection