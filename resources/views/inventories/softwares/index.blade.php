@extends('layouts.user_type.auth')

@section('content')

<h1>Listado de Softwares</h1>

@can('softwares.create')
    <a href="{{ route('softwares.create') }}" class="btn btn-primary mb-3">Agregar Software</a>
@endcan


<form method="GET" action="{{ route('softwares.index') }}" class="mb-4">
    <div class="row align-items-center">
        <!-- Filtro de estado (Activo/Inactivo) -->
        <div class="col-md-3 mb-3">
            <label for="status" class="form-label d-flex align-items-center">
                <i class="fas fa-toggle-on me-2"></i> Estado
            </label>
            <select name="status" class="form-select" id="status" onchange="this.form.submit()">
                <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>Activos</option>
                <option value="inactive" {{ request()->get('status') == 'inactive' ? 'selected' : '' }}>Desactivados</option>
            </select>
        </div>

        <!-- Buscador -->
        <div class="col-md-6 mb-3">
            <label for="search" class="form-label d-flex align-items-center">
                <i class="fas fa-search me-2"></i> Buscar software
            </label>
            <input type="text" name="search" class="form-control" id="search" placeholder="Buscar..." value="{{ request()->get('search') }}">
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
                @if (request()->get('status') == 'inactive')
                <th>#</th>
                <th>Nombre</th>
                <th>Fecha de Eliminación</th>
                <th>Acciones</th>
                @else
                <th>#</th>
                <th>Nombre</th>
                <th>Versión</th>
                <th>Fabricante</th>
                <th>Licencia</th>
                <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($softwares as $software)
            <tr>
                @if (request()->get('status') == 'inactive')
                <td>{{ $software->id }}</td>
                <td>{{ $software->software_name }}</td>
                <td>{{ $software->deleted_at }}</td>
                <td>
                    @can('softwares.restore')
                    <form action="{{ route('softwares.restore', $software->id) }}" class="formulario-restaurar" method="POST">
                        @csrf
                        @method('PUT')
                        <button id="btn-restore-software" class="btn btn-cyan-800 mb-3" type="submit">Restaurar</button>
                    </form>
                    @endcan

                </td>
                @else
                <td>{{ $software->id }}</td>
                <td>{{ $software->software_name }}</td>
                <td>{{ $software->version }}</td>
                <td>{{ $software->manufacturer->name }}</td>
                <td>
                    @if ($software->type == 'free')
                    No usa licencia
                    @else
                    @can('licencias.index')
                    <a title="Ver Licencias" href="{{ route('licenses.index', ['software_id' => $software->id]) }}" class="btn btn-cyan-800">
                        <i class="fa fa-key"></i>
                    </a>
                    @endcan

                    @endif
                </td>
                <td>
                    @can('softwares.index')
                    <a href="{{ route('softwares.index', $software->id) }}" title="Ver Software" class="btn btn-cyan-800">
                        <i class="bx bxs-show"></i>
                    </a>
                    @endcan

                    @can('softwares.edit')
                    <a href="{{ route('softwares.edit', $software->id) }}" title="Editar Software" class="btn btn-green-600">
                        <i class='bx bxs-edit-alt'></i>
                    </a>
                    @endcan

                    @can('softwares.destroy')
                    <form action="{{ route('softwares.destroy', $software->id) }}" method="POST" style="display:inline;" class="formulario-eliminar">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-red-800">
                            <i class="bx bxs-trash"></i>
                        </button>
                    </form>
                    @endcan
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>

    </table>
</div>

<!-- Paginación solo si es una instancia de paginación -->
<div class="pagination-container">
    @if($softwares instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $softwares->appends(request()->query())->links('pagination::bootstrap-4') }}
    @endif
</div>



@include('components.script-btn') <!-- Incluir scripts necesarios -->

<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script> <!-- Cargar scripts de tablas -->

@if (session('success')) <!-- Mostrar mensaje de opción si hay un estado en la sesión -->
<script>
    $(document).ready(function() {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: "{{ session('success') }}", // Muestra el mensaje de sesión
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
            text: "{{ session('warning') }}", // Muestra el mensaje de sesión
            timer: 3000,
            showConfirmButton: false
        });
    });
</script>
@endif


@endsection