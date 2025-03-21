@extends('layouts.user_type.auth')

@section('content')
    <h1>Listado de Insumos</h1>

    @can('supply.create')
    @if (request()->has('category_id') && request()->input('category_id') !== 'all')
        <a href="{{ route('supplies.create', ['category_id' => request()->input('category_id')]) }}"
            class="btn btn-primary">Agregar Insumo</a>
    @endif
    @endcan
    <form method="GET" action="{{ route('supplies.index') }}" class="mb-4">
        <div class="row align-items-center">
            <input type="hidden" name="category_id" value="{{ request()->get('category_id') }}">
            <!-- Filtro de estado (Activo/Inactivo) -->
            <div class="col-md-3 mb-3">
                <label for="status" class="form-label d-flex align-items-center">
                    <i class="fas fa-toggle-on me-2"></i> Estado
                </label>
                <select name="status" class="form-select" id="status" onchange="this.form.submit()">
                    <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>Activos</option>
                    <option value="inactive" {{ request()->get('status') == 'inactive' ? 'selected' : '' }}>Desactivados
                    </option>
                </select>
            </div>

            <!-- Buscador -->
            <div class="col-md-6 mb-3">
                <label for="search" class="form-label d-flex align-items-center">
                    <i class="fas fa-search me-2"></i> Buscar suministro
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
                    @if (request()->get('status') == 'inactive')
                        <th>#</th>
                        <th>Suministro</th>
                        <th>Fecha de Eliminación</th>
                        <th>Acciones</th>
                    @else
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($supplies as $supply)
                    <tr>
                        @if (request()->get('status') == 'inactive')
                            <td>{{ $supply->id }}</td>
                            <td>{{ $supply->name }}</td>
                            <td>{{ $supply->deleted_at }}</td>
                            <td>
                                @can('supply.restore')
                                <form action="{{ route('supplies.restore', $supply->id) }}" method="POST"
                                    class="formulario-restaurar">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-success">Restaurar</button>
                                </form>
                                @endcan
                            </td>
                        @else
                            <td>{{ $supply->name }}</td>
                            <td>{{ $supply->category->name }}</td>
                            <td>{{ $supply->quantity }}</td>
                            <td>{{ $supply->unit }}</td>
                            <td>{{ ucfirst($supply->status) }}</td>
                            <td>
                                @can('supply.index')
                                <a href="{{ route('supplies.show', $supply->id) }}" title="Ver Suministro"
                                    class="btn btn-cyan-800">
                                    <i class="bx bxs-show"></i>
                                </a>
                                @endcand
                                @can('supply.edit')
                                <a href="{{ route('supplies.edit', $supply->id) }}" title="Editar Suministro"
                                    class="btn btn-green-600">
                                    <i class="bx bxs-edit"></i>
                                </a>
                                @endcand
                                @can('supply.destroy')
                                <form action="{{ route('supplies.destroy', $supply->id) }}" method="POST"
                                    style="display:inline;" class="formulario-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-red-800">
                                        <i class="bx bxs-trash"></i>
                                    </button>
                                </form>
                                @endcand
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación solo si es una instancia de paginación -->
    <div class="pagination-container">
        @if ($supplies instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $supplies->appends(request()->query())->links('pagination::bootstrap-4') }}
        @endif
    </div>

    @include('components.script-btn') <!-- Incluir scripts necesarios -->

    <script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script> <!-- Cargar scripts de tablas -->

    @if (session('success'))
        <!-- Mostrar mensaje de opción si hay un estado en la sesión -->
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
    </div>
@endsection
