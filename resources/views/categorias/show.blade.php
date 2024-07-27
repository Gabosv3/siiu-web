@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Detalles de la Categoría</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Nombre: {{ $categoria->nombre }}</h5>
            <p class="card-text">Código: {{ $categoria->codigo }}</p>
            <a href="{{ route('categorias.index') }}" class="btn btn-primary">Volver</a>
        </div>
    </div>
</div>
@endsection