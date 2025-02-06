
@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Emulación de Bodega</h1>

    <!-- Listado de estantes y posiciones -->
    <div class="row">
        @foreach($shelves as $shelf)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $shelf->name }}</h2>
                </div>
                <div class="card-body">
                    <div class="shelf d-flex flex-wrap">
                        @foreach($shelf->positions as $position)
                        <div class="position m-2 p-2 border {{ $position->estado === 'occupied' ? 'bg-success text-white' : 'bg-light' }}">
                            @if($position->hardware)
                            {{ $position->hardware->inventory_code }}
                            @else
                            Empty
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Formulario para asignar hardware a una posición -->
    <div class="card my-4">
        <div class="card-header">
            <h3>Asignar Hardware a Posición</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('assign.hardware') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="hardware_id">Selecciona Hardware:</label>
                    <select name="hardware_id" class="form-control">
                        @foreach($hardwares as $hardware)
                        <option value="{{ $hardware->id }}">{{ $hardware->inventory_code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="position_id">Selecciona Posición:</label>
                    <select name="position_id" class="form-control">
                        @foreach($shelves as $shelf)
                        @foreach($shelf->positions as $position)
                        @if($position->estado === 'empty')
                        <option value="{{ $position->id }}">Posición {{ $position->id }} en {{ $shelf->name }}</option>
                        @endif
                        @endforeach
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Asignar Hardware</button>
            </form>
        </div>
    </div>

    <!-- Formulario para crear un nuevo estante -->
    <div class="card my-4">
        <div class="card-header">
            <h3>Crear Nuevo Estante</h3>
        </div>
        <div class="card-body">
            <form id="shelf-form">
                @csrf
                <div class="form-group">
                    <label for="shelf-name">Nombre del Estante:</label>
                    <input type="text" id="shelf-name" name="name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Crear Estante</button>
            </form>
        </div>
    </div>

    <!-- Formulario para agregar posiciones a estantes -->
    <div class="card my-4">
        <div class="card-header">
            <h3>Agregar Posición a Estante</h3>
        </div>
        <div class="card-body">
            <form id="addPositionForm">
                @csrf
                <div class="form-group">
                    <label for="shelf">Selecciona un estante:</label>
                    <select name="shelf_id" id="shelf" class="form-control" required>
                        @foreach($shelves as $shelf)
                        <option value="{{ $shelf->id }}">{{ $shelf->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="estado">Estado de la posición:</label>
                    <select name="estado" id="estado" class="form-control">
                        <option value="empty">Empty</option>
                        <option value="occupied">Occupied</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-info">Agregar posición</button>
            </form>
            <div id="successMessage" class="mt-3" style="display: none; color: green;">Posición creada con éxito.</div>
        </div>
    </div>
</div>

<!-- Scripts para manejar AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Crear nuevo estante
        $('#shelf-form').on('submit', function(e) {
            e.preventDefault(); // Evitar envío tradicional

            const shelfName = $('#shelf-name').val();

            $.ajax({
                url: "{{ route('shelves.store') }}",
                method: 'POST',
                data: {
                    name: shelfName,
                    _token: '{{ csrf_token() }}' // Token CSRF
                },
                success: function(response) {
                    // Agregar el nuevo estante a la lista y seleccionar
                    $('#shelves-list').append('<div>Estante: ' + response.shelf.name + '</div>');
                    $('#shelf').append('<option value="' + response.shelf.id + '">' + response.shelf.name + '</option>');
                    $('#shelf-name').val(''); // Limpiar el campo
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        // Agregar nueva posición
        $('#addPositionForm').on('submit', function(e) {
            e.preventDefault(); // Prevenir envío tradicional

            $.ajax({
                url: "{{ route('positions.store') }}",
                method: 'POST',
                data: $(this).serialize(), // Enviar datos del formulario
                success: function(response) {
                    // Mostrar mensaje de éxito
                    $('#successMessage').show();
                    // Agregar nueva posición al select de posiciones disponibles
                    $('select[name="position_id"]').append(
                        `<option value="${response.position.id}">Posición ${response.position.id} en Estante ${response.position.shelf.name}</option>`
                    );
                    // Limpiar el formulario
                    $('#addPositionForm')[0].reset();
                },
                error: function(xhr) {
                    alert('Error al agregar la posición: ' + xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
