@extends('layouts.user_type.auth')

@section('content')

<h1>Lista de Licencias</h1>

<div class="form-group">
    <form action="{{ route('licenses.index') }}" method="GET">
        <label for="software_id">Filtrar por Software:</label>
        <select name="software_id" id="software_id" class="form-control" onchange="this.form.submit()">
            <option value="">Selecciona un software</option>
            @foreach($softwares as $soft)
            <option value="{{ $soft->id }}" {{ $software && $software->id == $soft->id ? 'selected' : '' }}>
                {{ $soft->software_name }} ({{ $soft->version }})
            </option>
            @endforeach
        </select>
    </form>
</div>
@if($software)
@can('licencias.create')
<a class="btn btn-primary" href="{{ route('licenses.create', ['software_id' => $software->id]) }}" id="addLicenseButton">
    Agregar Licencia
</a>
@endcan

@endif

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <!-- Botones de navegación para las pestañas -->
        <button class="nav-link active" id="nav-categories-tab" data-bs-toggle="tab" data-bs-target="#nav-categories" type="button" role="tab" aria-controls="nav-categories" aria-selected="true">LICENCIAS</button>
        <button class="nav-link" id="nav-deactivated-tab" data-bs-toggle="tab" data-bs-target="#nav-deactivateds" type="button" role="tab" aria-controls="nav-deactivateds" aria-selected="false">DESACTIVADOS</button>
    </div>
</nav>


<div class="tab-content" id="nav-tabContent">
    <!-- Contenido de la pestaña de categorías -->
    <div class="tab-pane fade show active" id="nav-categories" role="tabpanel" aria-labelledby="nav-categories-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <table id="Principal" class="table align-items-center mb-0 text-center" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Software</th>
                        <th>Clave de Licencia</th>
                        <th>Fecha de Compra</th>
                        <th>Fecha de Expiración</th>
                        <th>Maximo de Dispositivos</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($licenses as $license)
                    <tr>
                        <td>{{ $license->software->software_name }}</td>
                        <td>{{ $license->license_key }}</td>
                        <td>{{ $license->purchase_date }}</td>
                        <td>{{ $license->expiration_date }}</td>
                        <td>{{ $license->max_devices }}</td>
                        <td>{{ $license->status }}</td>
                        <td>

                            @can('licencias.index')
                            <a href="{{ route('licenses.show', $license->id) }}" title="Ver licencia" class="btn btn-cyan-800"><i class="fa fa-eye"></i></a>
                            @endcan

                            @can('licencias.edit')
                            <a href="{{ route('licenses.edit', $license->id) }}" title="Editar licencia" class="btn btn-green-600"><i class='bx bxs-edit-alt'></i></a>
                            @endcan

                            @can('licencias.destroy')
                            <form action="{{ route('licenses.destroy', $license->id) }}" method="POST" style="display:inline;" class="formulario-eliminar">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Eliminar licencia" class="btn btn-red-800"><i class='bx bxs-trash'></i></button>
                            </form>
                            @endcan

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron licencias.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    <!-- Contenido de la pestaña de desactivados -->
    <div class="tab-pane fade" id="nav-deactivateds" role="tabpanel" aria-labelledby="nav-deactivated-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <table id="restaurar" class="table align-items-center mb-0 text-center" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOMBRE</th>
                        <th>CODIGO</th>
                        <th>FECHA ELIMINACION</th>
                        <th class="w-15">RESTORE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($licesesdeleted as $license)
                    <tr>
                        <td>{{ $license->id }}</td>
                        <td>{{ $license->software->software_name }}</td>
                        <td>{{ $license->license_key }}</td>
                        <td>{{ $license->deleted_at }}</td>
                        <td>
                            @can('licencias.restore')
                            <form action="{{ route('licenses.restore', $license->id) }}" class="formulario-restaurar" method="POST">
                                @csrf
                                @method('PUT')
                                <button id="btn-restore-license" class="btn btn-cyan-800 mb-3" type="submit">Restore</button>
                            </form>
                            @endcan

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Tabla de licencias -->



@include('components.script-btn') <!-- Incluir scripts necesarios -->

<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script> <!-- Cargar scripts de tablas -->


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

        initializeSelect2('#software_id');
    });
    document.addEventListener('DOMContentLoaded', function() {
        const softwareSelect = document.getElementById('software_id');
        const addLicenseButton = document.getElementById('addLicenseButton');

        // Función para validar el estado del botón
        function validateButton() {
            // Deshabilita el botón si no hay un valor seleccionado o es vacío
            addLicenseButton.disabled = !softwareSelect.value;
        }

        // Comprobar al cargar la página
        validateButton();

        // Añadir evento de cambio al select
        softwareSelect.addEventListener('change', function() {
            validateButton(); // Validar el botón al cambiar el select
        });
    });
</script>

@if (session('success'))
<script>
    $(document).ready(function() {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@elseif (session('error'))
<script>
    $(document).ready(function() {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif
@endsection