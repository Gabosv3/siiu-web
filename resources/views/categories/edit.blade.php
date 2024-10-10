@extends('layouts.user_type.auth')

@section('content')
<div class="container card" style="min-height: 70vh;">
    <h1 class="text-center mb-3">Edit Category</h1>

    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="row g-3" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- Vista previa de la imagen -->
        <div class="col-md-5 d-flex justify-content-center align-items-center bg-gradient-3 rounded-1 m-3">
            <img id="imagePreview" class="image-preview img-fluid rounded" src="{{ asset($category->image) }}" alt="Image Preview" style="max-width: 400px;height: auto;">
        </div>

        <!-- Formulario de entrada -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label">NOMBRE:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" required>
                <div class="invalid-feedback">
                    Por favor, ingrese un nombre válido (solo letras y espacios).
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">DESCRIPCION:</label>
                <textarea class="form-control" id="description" name="description" required>{{ old('description', $category->description) }}</textarea>
                <div class="invalid-feedback">
                    Por favor, ingrese una descripción.
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">IMAGEN:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage()">
                <div class="invalid-feedback">
                    Por favor, seleccione una imagen.
                </div>
            </div>
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">ACTUALIZAR</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Selecciona el formulario para la validación
        var form = document.querySelector('.needs-validation');
        // Selecciona el campo de entrada de nombre
        var nameInput = document.getElementById('name');

        // Agrega un evento al campo de nombre para validar la entrada
        nameInput.addEventListener('input', function() {
            var pattern = /^[A-Za-z\s]+$/; // Patrón para letras y espacios
            if (pattern.test(nameInput.value)) {
                // Si el patrón es válido
                nameInput.classList.remove('is-invalid');
                nameInput.classList.add('is-valid');
            } else {
                // Si el patrón es inválido
                nameInput.classList.remove('is-valid');
                nameInput.classList.add('is-invalid');
            }
        });

        // Maneja el envío del formulario
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                // Si el formulario no es válido, previene el envío
                event.preventDefault();
                event.stopPropagation();
            }
            // Agrega clase de validación al formulario
            form.classList.add('was-validated');
        }, false);
    }, false);
</script>
@endsection
