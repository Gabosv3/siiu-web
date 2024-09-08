@extends('layouts.user_type.auth')

@section('content')
<div class="container card" style="min-height: 70vh;">
    <h1 class="text-center mb-3">Ver Categoría</h1>

    <div class="row g-3">
        <!-- Vista previa de la imagen -->
        <div class="col-md-5 d-flex justify-content-center align-items-center bg-gradient-3 rounded-1 m-3">
            <img id="imagePreview" class="image-preview img-fluid rounded" src="{{ asset($categoria->imagen) }}" alt="Vista previa de la imagen" style="max-width: 400px;height: auto;">
        </div>

        <!-- Detalles de la categoría -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Nombre:</label>
                <p>{{ $categoria->nombre }}</p>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción:</label>
                <p>{{ $categoria->descripcion }}</p>
            </div>

            <div class="col-md-12 text-end">
                <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-primary">Editar</a>
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>
@endsection