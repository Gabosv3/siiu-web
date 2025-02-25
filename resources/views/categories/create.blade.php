@extends('layouts.user_type.auth')

@section('content')
<div class="container card" style="min-height: 70vh;">
    <h1 class="text-center mb-3">Crear Categoria</h1>

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
                <label for="description" class="form-label">TIPO:</label>
                <select name="type" class="form-control" id="type" required>
                    <option value="" disabled selected> Seleccione una tipo</option>
                    <option value="Equipo">Equipo</option>
                    <option value="Insumo">Insumo</option>

                </select>
                <div class="invalid-feedback">
                    Por favor, seleccione una tipo.
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">IMAGEN:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage()" required>
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

<script src="{{ asset('assets/js/Categories/Create.js') }}"></script>
@endsection