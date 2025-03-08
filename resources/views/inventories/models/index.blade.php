@extends('layouts.user_type.auth')

@section('content')
<div class="container mb-3">
    <h2>Modelos</h2>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="{{ route('models.create') }}" class="btn bg-gradient-2 me-2">Crear Modelo</a>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCharacteristicModal"> Agregar Característica</button>
    </div>

    <form method="GET" action="{{ route('models.index') }}" class="mb-4">
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
                <i class="fas fa-search me-2"></i> Buscar modelo
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
                <th>Modelo</th>
                <th>Fecha de Eliminación</th>
                <th>Acciones</th>
                @else
                <th>#</th>
                <th>Modelo</th>
                <th>Fecha de creación</th>
                <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($models as $model)
            <tr>
                @if (request()->get('status') == 'inactive')
                <td>{{ $model->id }}</td>
                <td>{{ $model->name }}</td>
                <td>{{ $model->deleted_at }}</td>
                <td>
                    <form action="{{ route('models.restore', $model->id) }}" method="POST" class="formulario-restaurar">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-success">Restaurar</button>
                    </form>
                </td>
                @else
                <td>{{ $model->id }}</td>
                <td>{{ $model->name }}</td>
                <td>{{ $model->created_at }}</td>
                <td>
                    <a href="{{ route('models.show', $model->id) }}" title="Ver Modelo" class="btn btn-cyan-800">
                        <i class="bx bxs-show"></i>
                    </a>
                    <a href="{{ route('models.edit', $model->id) }}" title="Editar Modelo" class="btn btn-green-600">
                        <i class="bx bxs-edit"></i>
                    </a>
                    <form action="{{ route('models.destroy', $model->id) }}" method="POST" style="display:inline;" class="formulario-eliminar">
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
    @if($models instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $models->appends(request()->query())->links('pagination::bootstrap-4') }}
    @endif
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Crear Modelos</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para agregar características -->
<div class="modal fade" id="addCharacteristicModal" tabindex="-1" role="dialog"
aria-labelledby="addCharacteristicModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addCharacteristicModalLabel">Agregar Característica</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar">
                
            </button>
        </div>
        <form id="addCharacteristicForm">
            <div class="modal-body">
                <div class="form-group">
                    <label for="characteristic_name">Nombre de la característica:</label>
                    <input type="text" id="characteristic_name" name="characteristic_name" class="form-control"
                        placeholder="Nombre de la característica" required>
                </div>
                <div class="form-group">
                    <label for="characteristic_description">Descripción de la característica:</label>
                    <textarea id="characteristic_description" name="characteristic_description" class="form-control"
                        placeholder="Descripción de la característica" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveCharacteristic">Guardar</button>
            </div>
        </form>
    </div>
</div>
</div>






@include('components.script-btn')

<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#saveCharacteristic').on('click', function() {
            var name = $('#characteristic_name').val();
            var description = $('#characteristic_description').val();

            // Validar que el nombre no esté vacío
            if (name.trim() === '' || description.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo obligatorio',
                    text: 'El nombre y la descripción de la característica es obligatorio.',
                });
                return;
            }

            // Enviar los datos al servidor mediante AJAX
            $.ajax({
                url: '{{ route("characteristics.store") }}', // Ruta al controlador
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: name,
                    description: description
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Guardado!',
                        text: 'La característica se agregó correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Agregar la nueva característica a la tabla
                    var newRow = `
                        <tr>
                            <td>${response.id}</td>
                            <td>${response.name}</td>
                            <td>${response.description || 'Sin descripción'}</td>
                        </tr>
                    `;
                    $('#characteristicsTable tbody').append(newRow);

                    // Cerrar el modal y limpiar el formulario
                    $('#addCharacteristicModal').modal('hide');
                    $('#addCharacteristicForm')[0].reset();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al guardar la característica. Inténtalo de nuevo.',
                    });
                }
            });
        });
    });
</script>





@endsection
