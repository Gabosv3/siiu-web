@extends('layouts.user_type.auth')

@section('content')
<div class="card mt-4 p-3">
    <h2 class="mb-5">Actualizar Departamento/Sección/Unidad</h2>

    <div class="row">
        <!-- Columna para el mapa -->
        <div class="col-md-6 map-container">
            <div id="map"></div>
        </div>


        <!-- Columna para el formulario -->
        <div class="col-md-6">
            <form action="{{ route('departaments.update', $departament->id) }}" method="POST" class="needs-validation" >
                @csrf
                @method('PUT') <!-- Método PUT para la actualización -->

                <div class="mb-3">
                    <label for="name" class="form-label">NOMBRE:</label>
                    <input type="text" class="form-control" id="input-department-name" name="name" value="{{ old('name', $departament->name) }}" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" required>
                    <div class="invalid-feedback">
                        ingrese un nombre válido (solo letras y espacios).
                    </div>
                </div>

                <div class="mb-3">
                    <label for="code" class="form-label">CÓDIGO:</label>
                    <input type="text" class="form-control" id="input-department-code" name="code" value="{{ old('code', $departament->code) }}" pattern="\d+" required>
                    <div class="invalid-feedback">
                        ingrese un código válido (solo números).
                    </div>
                </div>

                <select class="form-control js-select-manager" id="input-department-manager" name="manager" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $departament->manager == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>

                <div class="mb-3">
                    <label for="description" class="form-label">DESCRIPCIÓN:</label>
                    <textarea class="form-control" id="input-department-description" name="description" rows="3" required>{{ old('description', $departament->description) }}</textarea>
                    <div class="invalid-feedback">
                        por favor, ingrese una descripción.
                    </div>
                </div>

                <div class="mb-3">
                    <input type="text" id="latitude" name="latitude" placeholder="Latitud" class="form-control" value="{{ old('latitude', $departament->latitude) }}" readonly>
                </div>

                <div class="mb-3">
                    <input type="text" id="longitude" name="longitude" placeholder="Longitud" class="form-control" value="{{ old('longitude', $departament->longitude) }}" readonly>
                </div>

                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">ACTUALIZAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/departments/edit.js') }}"></script>

@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Error de Validación',
                html: `
                    <ul style="text-align: left;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonText: 'Aceptar'
            });
        });
    </script>
@endif


@endsection
