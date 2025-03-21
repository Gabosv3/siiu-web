@extends('layouts.user_type.auth')

@section('content')
<div class="card mt-4 p-3">
<h2 class="mb-5">Crear Departamento/Sección/Unidad</h2>
    <div class="row">
        <!-- Columna para el mapa -->
        <div class="col-md-6 map-container">
            <div id="map"></div>
        </div>

        <!-- Columna para el formulario -->
        <div class="col-md-6">
            <form action="{{ route('departaments.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <!-- Campo para el nombre -->
                <div class="mb-3">
                    <label for="name" class="form-label">NOMBRE:</label>
                    <input type="text" class="form-control" id="input-department-name" name="name" 
                           value="{{ old('name') }}" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" required>
                    <div class="invalid-feedback">
                        ingrese un nombre válido (solo letras y espacios).
                    </div>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

               <!-- Campo para el código -->
                <div class="mb-3">
                    <label for="code" class="form-label">CODIGO:</label>
                    <input type="text" class="form-control" id="input-department-code" name="code" 
                           value="{{ old('code') }}" pattern="\d+" required>
                    <div class="invalid-feedback">
                        ingrese un código válido (solo números).
                    </div>
                    @error('code')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Campo para la descripción -->
                <div class="mb-3">
                    <label for="description" class="form-label">DESCRIPCION:</label>
                    <textarea class="form-control" id="input-department-description" name="description" rows="3" required>{{ old('description') }}</textarea>
                    <div class="invalid-feedback">
                        por favor, ingrese una descripción.
                    </div>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Campo para la latitud -->
                <div class="mb-3">
                    <input type="text" id="latitude" name="latitude" placeholder="Latitud" class="form-control" value="{{ old('latitude') }}" readonly>
                </div>

                <!-- Campo para la longitud -->
                <div class="mb-3">
                    <input type="text" id="longitude" name="longitude" placeholder="Longitud" class="form-control" value="{{ old('longitude') }}" readonly>
                </div>

                <!-- Botón para guardar el nuevo departamento -->
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">GUARDAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/departments/create.js') }}"></script>

@endsection
