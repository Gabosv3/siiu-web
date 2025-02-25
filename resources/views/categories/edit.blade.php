@extends('layouts.user_type.auth')

@section('content')
<div class="container card" style="min-height: 70vh;">
    <h1 class="text-center mb-3">Editar Categoria</h1>

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
                <label for="description" class="form-label">TIPO:</label>
                <select name="type" class="form-control" id="type" required>
                    <option value="" disabled>Seleccione un tipo</option>
                    <option value="Equipo" {{ $category->type == 'Equipo' ? 'selected' : '' }}>Equipo</option>
                    <option value="Insumo" {{ $category->type == 'Insumo' ? 'selected' : '' }}>Insumo</option>
                </select>
                <div class="invalid-feedback">
                    Por favor, seleccione un tipo.
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
                <button type="submit" class="btn btn-primary">ACTUALIZAR</button>
            </div>
        </div>
    </form>
</div>

<script src="{{ asset('assets/js/Categories/edit.js') }}"></script>
@endsection