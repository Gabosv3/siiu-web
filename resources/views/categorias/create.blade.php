@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Crear Categoría</h1>

    <form action="{{ route('categorias.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" pattern="[A-Za-z\s]+" required>
            <div class="invalid-feedback">
                Por favor, ingrese un nombre válido (solo letras y espacios).
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Crear</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('.needs-validation');
        var nombreInput = document.getElementById('nombre');

        nombreInput.addEventListener('input', function() {
            var pattern = /^[A-Za-z\s]+$/;
            if (pattern.test(nombreInput.value)) {
                nombreInput.classList.remove('is-invalid');
                nombreInput.classList.add('is-valid');
            } else {
                nombreInput.classList.remove('is-valid');
                nombreInput.classList.add('is-invalid');
            }
        });

        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }, false);
</script>
@endsection