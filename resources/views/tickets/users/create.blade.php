@extends('layouts.user_type.auth')

@section('content')
<div class="container card d-flex justify-content-center align-items-center" style="min-height: 75vh;">

    <form action="{{ route('crear.tickets') }}" class="row g-3 w-60 justify-content-center align-items-center" method="POST" id="ticket-form">
        @csrf
        <h2>Crear Ticket</h2>

        <div class="col-md-4 col-sm-12 mb-3">
            <label for="user_id" class="form-label">Usuario</label>
            <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
        </div>

        <div class="col-md-4 col-sm-12 mb-3">
            <label for="user_id" class="form-label">Departamento</label>
            <input type="text" class="form-control" value="{{ auth()->user()->departament->name }}" readonly>
        </div>

        <div class="col-md-4 col-sm-12 mb-3">
            <label for="title_id">Título</label>
            <select name="title_id" id="title_id" class="form-control">
                @foreach ($titles as $title)
                <option value="{{ $title->id }}">{{ $title->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-8 col-sm-12 mb-3">
            <label for="description" class="form-label">Descripción del problema</label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>

        <div class="col-md-8 text-end">
            <button type="submit" class="btn btn-primary" id="btn-submit">
                Crear Ticket
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        function initializeSelect2(selector) {
            $(selector).select2({
                placeholder: "Seleccione una opción",
                theme: "bootstrap-5",
                width: '100%',
            });
        }
        initializeSelect2('#title_id');

        // Capturar el envío del formulario
        $("#ticket-form").on("submit", function(event) {
            let submitButton = $("#btn-submit");

            // Deshabilitar el botón y cambiar el texto
            submitButton.prop("disabled", true).text("Procesando...");

            // Mostrar una alerta de carga con SweetAlert2
            Swal.fire({
                title: "Enviando...",
                text: "Por favor, espera mientras se procesa la solicitud.",
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    });
</script>

@if (session('success'))
<script>
    $(document).ready(function() {
        $("#btn-submit").prop("disabled", false).text("Crear Ticket");
        
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
        // Restaurar el botón en caso de error
        $("#btn-submit").prop("disabled", false).text("Crear Ticket");

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

@if ($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Restaurar el botón en caso de errores de validación
        $("#btn-submit").prop("disabled", false).text("Crear Ticket");

        Swal.fire({
            icon: 'error',
            title: 'Error de Validación',
            html: `
                <ul style="text-align: left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            confirmButtonText: 'Aceptar'
        });
    });
</script>
@endif
@endsection
