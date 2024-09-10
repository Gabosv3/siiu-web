@extends('layouts.user_type.auth')

@section('content')
<div class="container card" style="min-height: 70vh;">
    <h1 class="text-center mb-3">Editar Categoría</h1>

    <form action="{{ route('categorias.update', $categoria->id) }}" method="POST" class="row g-3" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- Vista previa de la imagen -->
        <div class="col-md-5 d-flex justify-content-center align-items-center bg-gradient-3 rounded-1 m-3">
            <img id="imagePreview" class="image-preview img-fluid rounded" src="{{ asset($categoria->imagen) }}" alt="Vista previa de la imagen" style="max-width: 400px;height: auto;">
        </div>

        <!-- Formulario de entrada -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre) }}" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" required>
                <div class="invalid-feedback">
                    Por favor, ingrese un nombre válido (solo letras y espacios).
                </div>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required>{{ old('descripcion', $categoria->descripcion) }}</textarea>
                <div class="invalid-feedback">
                    Por favor, ingrese una descripción.
                </div>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" onchange="previewImage()">
                <div class="invalid-feedback">
                    Por favor, seleccione una imagen.
                </div>
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </div>
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