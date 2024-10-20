@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Emulación de Bodega</h1>

    <!-- Listado de estantes y posiciones -->
    @foreach($shelves as $shelf)
    <h2>{{ $shelf->name }}</h2>
    <div class="shelf">
        @foreach($shelf->positions as $position)
        <div class="position {{ $position->estado }}">
            @if($position->hardware)
            {{ $position->hardware->inventory_code }}
            @else
            Empty
            @endif
        </div>
        @endforeach
    </div>
    @endforeach

    <!-- Formulario para asignar hardware a una posición -->
    <form action="{{ route('assign.hardware') }}" method="POST">
        @csrf
        <select name="hardware_id">
            @foreach($hardwares as $hardware)
            <option value="{{ $hardware->id }}">{{ $hardware->inventory_code }}</option>
            @endforeach
        </select>

        <select name="position_id">
            @foreach($shelves as $shelf)
            @if($shelf->positions->count() > 0)
            @foreach($shelf->positions as $position)
            @if($position->estado === 'empty')
            <option value="{{ $position->id }}">Posición {{ $position->id }} en {{ $shelf->name }}</option>
            @endif
            @endforeach
            @else
            <option disabled>No hay posiciones en {{ $shelf->name }}</option>
            @endif
            @endforeach
        </select>

        <button type="submit">Asignar Hardware</button>
    </form>

    <!-- Formulario para crear un nuevo estante -->
    <form id="shelf-form">
        @csrf
        <label for="shelf-name">Nombre del Estante:</label>
        <input type="text" id="shelf-name" name="name" required>
        <button type="submit">Crear Estante</button>
    </form>

    <!-- Lista de estantes creados dinámicamente -->
    <div id="shelves-list"></div>

    <!-- Formulario para agregar posiciones a estantes -->
    <form id="addPositionForm">
        @csrf
        <label for="shelf">Selecciona un estante:</label>
        <select name="shelf_id" id="shelf" required>
            @foreach($shelves as $shelf)
            <option value="{{ $shelf->id }}">{{ $shelf->name }}</option>
            @endforeach
        </select>

        <label for="estado">Estado de la posición:</label>
        <select name="estado" id="estado">
            <option value="empty">Empty</option>
            <option value="occupied">Occupied</option>
        </select>

        <button type="submit">Agregar posición</button>
    </form>

    <!-- Mensaje de éxito -->
    <div id="successMessage" style="display: none; color: green;">Posición creada con éxito.</div>

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
