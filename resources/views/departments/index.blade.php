@extends('layouts.user_type.auth')

@section('content')
    <h1>Departamentos/Secciones/Unidades</h1>
    @can('departamentos.create')
        <a href="{{ route('departaments.create') }}" class="btn bg-gradient-2 mb-3">Crear Departamento</a>
    @endcan


    <div class="row mb-3">
        <div class="col-md-12 map-container">
            <div id="map" style="max-height: 400px;"></div>
        </div>
    </div>

    <form method="GET" action="{{ route('departaments.index') }}" class="mb-4">
        <div class="row align-items-center">
            <!-- Filtro de estado (Activo/Inactivo) -->
            <div class="col-md-3 mb-3">
                <label for="status" class="form-label d-flex align-items-center">
                    <i class="fas fa-toggle-on me-2"></i> Estado
                </label>
                <select name="status" class="form-select" id="status" onchange="this.form.submit()">
                    <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Activos</option>
                    <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Desactivados</option>
                </select>
            </div>

            <!-- Buscador -->
            <div class="col-md-6 mb-3">
                <label for="search" class="form-label d-flex align-items-center">
                    <i class="fas fa-search me-2"></i> Buscar departamento
                </label>
                <input type="text" name="search" class="form-control" id="search" placeholder="Buscar..."
                    value="{{ request()->get('search') }}">
            </div>

            <!-- Selector de cantidad de registros por página -->
            <div class="col-md-3 mb-3">
                <label for="perPage" class="form-label d-flex align-items-center">
                    <i class="fas fa-list me-2"></i> Registros por página
                </label>
                <select name="perPage" class="form-select" id="perPage" onchange="this.form.submit()">
                    <option value="10" {{ request()->get('perPage') == '10' ? 'selected' : '' }}>10 registros</option>
                    <option value="20" {{ request()->get('perPage') == '20' ? 'selected' : '' }}>20 registros</option>
                    <option value="50" {{ request()->get('perPage') == '50' ? 'selected' : '' }}>50 registros</option>
                    <option value="all" {{ request()->get('perPage') == 'all' ? 'selected' : '' }}>Todos</option>
                </select>
            </div>
        </div>
    </form>


    <div class="table-responsive shadow-lg p-3 mb-5 bg-body rounded rounded-3">
        <table id="Principal" class="table align-items-center mb-0 text-center" style="width:100%">
            <thead class="align-middle bg-gradient-2">
                <tr>
                    <th>#</th>
                    <th>NOMBRE</th>
                    <th>CODIGO</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departments as $department)
                    <tr>
                        <td>{{ $department->id }}</td>
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->code }}</td>
                        <td>
                            @if ($status == 'inactive')
                                @can('departamentos.restore')
                                    <form action="{{ route('departamentos.restore', $department->id) }}" method="POST"
                                        style="display:inline;" class="formulario-restaurar">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-cyan-800">Restaurar</button>
                                    </form>
                                @endcan
                            @else
                                @can('departamentos.index')
                                <a href="{{ route('departaments.show', $department->id) }}" title="Ver departamento"
                                    class="btn btn-cyan-800"><i class='bx bxs-show'></i></a>
                                @endcan
                                @can('departamentos.edit')
                                <a href="{{ route('departaments.edit', $department->id) }}" title="Editar departamento"
                                    class="btn btn-green-600"><i class='bx bxs-edit-alt'></i></a>
                                @endcan

                                @can('departamentos.destroy')

                                @endcan


                                <form action="{{ route('departaments.destroy', $department->id) }}" method="POST"
                                    style="display:inline;" class="formulario-eliminar">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-red-800"><i class='bx bxs-trash'></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación solo si es una instancia de paginación -->
    <div class="pagination-container">
        @if ($departments instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $departments->appends(request()->query())->links('pagination::bootstrap-4') }}
        @endif
    </div>

    @include('components.script-btn')

    <script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>

    @if (session('status'))
        <!-- Mostrar mensaje de éxito si hay un estado en la sesión -->
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: "{{ session('status') }}", // Muestra el mensaje de sesión
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Coordenadas por defecto para centrar el mapa
            let defaultLat = 13.43931902478275;
            let defaultLng = -88.15837383270265;

            // Inicializar el mapa
            let map = L.map('map').setView([defaultLat, defaultLng], 16);

            // Capas base
            let osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            let satelliteLayer = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 18,
                    attribution: '© <a href="https://www.esri.com/en-us/arcgis/about-arcgis/overview">Esri</a>'
                });

            // Selector de capas
            let baseMaps = {
                "Map": osmLayer,
                "Satellite": satelliteLayer
            };
            L.control.layers(baseMaps).addTo(map);

            // Añadir marcadores para cada departamento
            let departments = @json($departments); // Convertir datos desde Laravel a JSON

            departments.forEach(function(department) {
                if (department.latitude && department.longitude) {
                    let marker = L.marker([department.latitude, department.longitude]).addTo(map);

                    // Contenido del popup con opciones
                    let popupContent = `
                <div>
                    <h5>${department.name}</h5>
                    <p>Latitud: ${department.latitude}<br>Longitud: ${department.longitude}</p>
                    <button onclick="navigateTo('${department.id}')">Ver Detalles</button>
                    <button onclick="editDepartment('${department.id}')">Editar</button>
                    <button onclick="EquiposDepartamento('${department.id}')">Equipos</button>
                </div>
            `;

                    marker.bindPopup(popupContent);
                }
            });

            // Funciones para los botones
            window.navigateTo = function(departmentId) {
                window.location.href = `/departaments/${departmentId}`;
            };

            window.editDepartment = function(departmentId) {
                window.location.href = `/departaments/${departmentId}/edit`;
            };

            window.EquiposDepartamento = function(departmentId) {
                window.location.href = `/Departamentos/equipos/${departmentId}`;
            };
        });
    </script>
@endsection
