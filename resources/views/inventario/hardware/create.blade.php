@extends('layouts.user_type.auth')

@section('content')
<div class="p-3">
    <h3>Crear Activo</h3>
    <form action="{{ route('hardwares.store') }}" method="POST">
        @csrf
        <!-- Estado -->
        <div class="card border border-dark p-3 my-3">
            <div class="card-header">General
                <hr>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <!-- Estado -->
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select id="estado" name="estado" class="form-control" required>
                            <option value="nuevo">Nuevo</option>
                            <option value="asignado">Asignado</option>
                        </select>
                    </div>

                    <!-- Fabricante -->
                    <div class="mb-3">
                        <label for="fabricante_id" class="form-label">Fabricante</label>
                        <div class="input-group">
                            <select id="fabricante_id" name="fabricante_id" class="form-control js-select-fabricante" data-show-subtext="true" data-live-search="true">
                                @foreach($fabricantes as $fabricante)
                                <option value="{{ $fabricante->id }}">{{ $fabricante->nombre }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#createFabricanteModal">Añadir nuevo</button>
                        </div>
                    </div>

                    <!-- Modal -->


                    <!-- Modelo -->
                    <div class="mb-3">
                        <label for="modelo_id" class="form-label">Modelo</label>
                        <select id="modelo_id" name="modelo_id" class="form-control">
                            @foreach($modelos as $modelo)
                            <option value="{{ $modelo->id }}">{{ $modelo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ubicación -->
                    <div class="mb-3">
                        <label for="ubicacion_id" class="form-label">Ubicación</label>
                        <select id="ubicacion_id" name="ubicacion_id" class="form-control js-select-ubicacion" data-show-subtext="true" data-live-search="true">
                            @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Columna de imagen -->
                <div class="col-lg-5 d-flex align-items-center">
                    <img src="" alt="Imagen de la categoría" class="img-fluid">
                </div>
            </div>
        </div>

        <!-- Nombre del hardware -->
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <!-- Categoría -->
        <div class="mb-3">
            <label for="categoria_id" class="form-label">Categoría</label>
            <select id="categoria_id" name="categoria_id" class="form-control" required>
                @foreach($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Conflictos -->
        <div class="mb-3">
            <label for="conflictos" class="form-label">Conflictos</label>
            <textarea id="conflictos" name="conflictos" class="form-control"></textarea>
        </div>

        <!-- Dueño -->
        <div class="mb-3">
            <label for="dueno_id" class="form-label">Dueño</label>
            <select id="dueno_id" name="dueno_id" class="form-control">
                <option value="">No asignado</option>
                @foreach($usuarios as $usuario)
                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Código de inventario -->
        <div class="mb-3">
            <label for="codigo_de_inventario" class="form-label">Código de Inventario</label>
            <input type="text" class="form-control" id="codigo_de_inventario" name="codigo_de_inventario" required>
        </div>

        <!-- Tags -->
        <div class="mb-3">
            <label for="tags" class="form-label">Tags</label>
            <select id="tags" name="tags[]" class="form-control selectpicker" multiple data-live-search="true">
                @foreach($tags as $tag)
                <option value="{{ $tag->id }}">{{ $tag->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Sistemas Asignados -->
        <div class="mb-3">
            <label for="sistemas_asignados" class="form-label">Sistemas Asignados</label>
            <select id="sistemas_asignados" name="sistemas_asignados[]" class="form-control selectpicker" multiple data-live-search="true">
                @foreach($sistemas as $sistema)
                <option value="{{ $sistema->id }}">{{ $sistema->nombre }}</option>
                @endforeach
            </select>
        </div>
        <!-- Botón de enviar -->
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>

    <div class="modal fade" id="createFabricanteModal" tabindex="-1" aria-labelledby="createFabricanteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFabricanteModalLabel">Añadir Nuevo Fabricante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createFabricanteForm">
                        <div class="mb-3">
                            <label for="nuevo_fabricante_nombre" class="form-label">Nombre del Fabricante</label>
                            <input type="text" class="form-control" id="nuevo_fabricante_nombre" name="nombre" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.js-select-fabricante').select2();
        $('.js-select-ubicacion').select2();

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
                    console.log('Success:', response);

                    // Cierra el modal
                    $('#createFabricanteModal').modal('hide');
                    $('.modal-backdrop').remove();//eliminamos el backdrop del modal
                    $('#nuevo_fabricante_nombre').val('');

                    // Muestra el alert con SweetAlert2
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Nuevo fabricante creado con éxito.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                    alert('Hubo un error al crear el fabricante.');
                }
            });
        });
    });
</script>
@endsection