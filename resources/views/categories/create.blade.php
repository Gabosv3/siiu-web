@extends('layouts.user_type.auth')

@section('content')
<div class="container card" style="min-height: 70vh;">
    <h1 class="text-center mb-3">Create Category</h1>

    <form action="{{ route('categories.store') }}" method="POST" class="row g-3" enctype="multipart/form-data">
        @csrf
        <!-- Image preview -->
        <div class="col-md-5 d-flex justify-content-center align-items-center bg-gradient-3 rounded-1 m-3">
            <img id="imagePreview" class="image-preview img-fluid rounded" src="#" alt="Image preview" style="max-width: 400px; height: auto; display: none;">
        </div>

        <!-- Input form -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label">NOMBRE:</label>
                <input type="text" class="form-control" id="name" name="name" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" required>
                <div class="invalid-feedback">
                    Por favor, ingrese un nombre válido (solo letras y espacios).
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">DESCRIPCION:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
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
                <button type="submit" class="btn btn-primary">CREAR</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('.needs-validation');
        var nameInput = document.getElementById('name');

        nameInput.addEventListener('input', function() {
            var pattern = /^[A-Za-z\s]+$/;
            if (pattern.test(nameInput.value)) {
                nameInput.classList.remove('is-invalid');
                nameInput.classList.add('is-valid');
            } else {
                nameInput.classList.remove('is-valid');
                nameInput.classList.add('is-invalid');
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

<script>
    function previewImage() {
        const fileInput = document.getElementById('image');
        const preview = document.getElementById('imagePreview');
        const file = fileInput.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }
</script>
@endsection
