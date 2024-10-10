@extends('layouts.user_type.auth')

@section('content')
<div class="container card" style="min-height: 70vh;">
    <h1 class="text-center mb-3">Ver Categoria</h1>

    <div class="row g-3">
        <!-- Imagen previsualizada -->
        <div class="col-md-5 d-flex justify-content-center align-items-center bg-gradient-3 rounded-1 m-3">
            <img id="imagePreview" class="image-preview img-fluid rounded" src="{{ asset($category->image) }}" alt="Image preview" style="max-width: 400px; height: auto;">
        </div>

        <!-- detalles de la categoría -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">NOMBRE:</label>
                <p>{{ $category->name }}</p>
            </div>

            <div class="mb-3">
                <label class="form-label">DESCRIPCION:</label>
                <p>{{ $category->description }}</p>
            </div>

            <div class="col-md-12 text-end">
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary">EDITAR</a>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">VOLVER</a>
            </div>
        </div>
    </div>
</div>
@endsection