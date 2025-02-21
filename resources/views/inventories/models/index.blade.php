@extends('layouts.user_type.auth')

@section('content')
<div class="container mb-3">
    <h2>Modelos</h2>
    <div class="d-flex justify-content-end align-items-center mb-3">
        <a href="{{ route('models.create') }}" class="btn bg-gradient-2 me-2">Crear Modelo</a>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCharacteristicModal"> Agregar Característica</button>
    </div>

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
           <button class="nav-link active" id="nav-models-tab" data-bs-toggle="tab" data-bs-target="#nav-models" type="button" role="tab" aria-controls="nav-models" aria-selected="true">Modelos</button>
            <button class="nav-link" id="nav-desactivados-tab" data-bs-toggle="tab" data-bs-target="#nav-desactivados" type="button" role="tab" aria-controls="nav-desactivados" aria-selected="false">Desactivados</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-models" role="tabpanel" aria-labelledby="nav-models-tab">
            <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
                <table id="Principal" class="table align-items-center mb-0 text-center">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Modelo</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Descripción</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha de creación</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($models as $key => $model)
                        <tr>
                            <td>{{ $model->id }}</td>
                            <td>{{ $model->name }}</td>
                            <td>{{ $model->created_at }}</td>
                            <td>{{ $model->updated_at }}</td>
                            <td>
                                <a href="{{ route('models.show', $model->id) }}" title="Ver Modelo" class="btn btn-cyan-800"><i class="bx bxs-show"></i></a>
                                <a href="{{ route('models.edit', $model->id) }}" title="Editar modelo" class="btn btn-green-600"><i class="bx bxs-edit"></i></a>
                                <form action="{{ route('models.destroy', $model->id) }}" method="POST" style="display:inline;" class="formulario-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Eliminar modelo" class="btn btn-red-800"><i class="bx bxs-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-desactivados" role="tabpanel" aria-labelledby="nav-desactivados-tab">
            <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
                <table id="restaurar" class="table align-items-center mb-0 text-center" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Modelo</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha de eliminación</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deletedModels as $key => $model)
                        <tr>
                            <td>{{ $model->id }}</td>
                            <td>{{ $model->name }}</td>
                            <td>{{ $model->deleted_at }}</td>
                            <td>
                                <form action="{{ route('models.restore', $model->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success">Restaurar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
