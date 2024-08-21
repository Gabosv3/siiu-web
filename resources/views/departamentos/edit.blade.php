@extends('layouts.user_type.auth')

@section('content')
<h1>Editar Departamento</h1>
<form action="{{ route('departamentos.update', $departamento->id) }}" method="POST" class="needs-validation" novalidate>
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $departamento->nombre }}" pattern="[A-Za-z\s]+" required>
        <div class="invalid-feedback">
            Por favor, ingrese un nombre válido (solo letras y espacios).
        </div>
    </div>
    <button type="submit" class="btn btn-primary" id="">Actualizar</button>
</form>

<script>
    // Espera a que el DOM esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        // Selecciona el formulario
        var form = document.querySelector('.needs-validation');

        // Añade un evento 'submit' al formulario para la validación
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);

        // Validación en tiempo real
        form.addEventListener('input', function(event) {
            var input = event.target;
            if (input.id === 'nombre') {
                var pattern = /^[A-Za-z\s]+$/;
                if (pattern.test(input.value)) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            }
        }, true);
    }, false);
</script>

</div>
@endsection