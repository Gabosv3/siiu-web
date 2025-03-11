@extends('layouts.user_type.auth')

@section('content')
<h1>CATEGORIAS</h1> <!-- Título de la página -->
<a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">CREAR CATEGORIA</a> <!-- Botón para crear una nueva categoría -->

<form method="GET" action="{{ route('categories.index') }}" class="mb-4">
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
        <div class="col-md-5 mb-3">
            <label for="search" class="form-label d-flex align-items-center">
                <i class="fas fa-search me-2"></i> Buscar categoría
            </label>
            <input type="text" name="search" class="form-control" id="search" placeholder="Buscar..." value="{{ request()->get('search') }}">
        </div>
        <!-- Selector de cantidad de registros por página -->
        <div class="col-md-2 mb-3">
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
        <!-- Filtro de tipo (Insumo/Equipo) -->
        <div class="col-md-2 mb-3">
            <label for="type" class="form-label d-flex align-items-center">
                <i class="fas fa-filter me-2"></i> Tipo
            </label>
            <select name="type" class="form-select" id="type" onchange="this.form.submit()">
                <option value="">Todos</option>
                <option value="insumo" {{ request()->get('type') == 'insumo' ? 'selected' : '' }}>Insumo</option>
                <option value="equipo" {{ request()->get('type') == 'equipo' ? 'selected' : '' }}>Equipo</option>
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
                <th>Categoría</th>
                <th>Fecha de Eliminación</th>
                <th>Acciones</th>
                @else
                <th>#</th>
                <th>Imagen</th>
                <th>Categoría</th>
                <th>Codigo</th>
                <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
            <tr>
                @if (request()->get('status') == 'inactive')
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->deleted_at }}</td>
                <td>
                    <form action="{{ route('categories.restore', $category->id) }}" method="POST" class="formulario-restaurar">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-success">Restaurar</button>
                    </form>
                </td>
                @else
                <td>{{ $category->id }}</td>
                <td>
                    @if($category->image) <!-- Verificar si hay imagen -->
                        <img src="{{ asset($category->image) }}" alt="Category {{ $category->name }}" class="img-thumbnail" style="height: 72px;">
                         @else
                        <span>Not available</span> <!-- Mensaje si no hay imagen -->
                    @endif
                </td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->code }}</td>

                <td>
                    <a href="{{ route('categories.show', $category->id) }}" title="Ver Categoría" class="btn btn-cyan-800">
                        <i class="bx bxs-show"></i>
                    </a>
                    <a href="{{ route('categories.edit', $category->id) }}" title="Editar Categoría" class="btn btn-green-600">
                        <i class="bx bxs-edit"></i>
                    </a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;" class="formulario-eliminar">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-red-800">
                            <i class="bx bxs-trash"></i>
                        </button>
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Paginación solo si es una instancia de paginación -->
<div class="pagination-container">
    @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $categories->appends(request()->query())->links('pagination::bootstrap-4') }}
    @endif
</div>


@include('components.script-btn') <!-- Incluir scripts necesarios -->

<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script> <!-- Cargar scripts de tablas -->

@if (session('status')) <!-- Mostrar mensaje de éxito si hay un estado en la sesión -->
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

@endsection
