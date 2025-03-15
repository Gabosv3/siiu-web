@extends('layouts.user_type.auth')

@section('content')
<div class="card mt-4 p-3">
<h2 class="mb-5">Crear Departamento/Seccion/Unidad</h2>
    <div class="row">
        <!-- Columna para el mapa -->

        <div class="col-md-6 map-container">
            <div id="map"></div>
        </div>

        <!-- Columna para el formulario -->
        <div class="col-md-6">
            <form action="{{ route('departaments.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf


                <div class="mb-3">
                    <label for="name" class="form-label">NOMBRE:</label>
                    <input type="text" class="form-control" id="input-department-name" name="name" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" required>
                    <div class="invalid-feedback">
                        ingrese un nombre válido (solo letras y espacios).
                    </div>
                </div>

               <!-- campo para el código  -->
                <div class="mb-3">
                    <label for="code" class="form-label">CODIGO:</label>
                    <input type="text" class="form-control" id="input-department-code" name="code" pattern="\d+" required>
                    <div class="invalid-feedback">
                        ingrese un código válido (solo números).
                    </div>
                </div>

                <!-- campo para el encargado -->
                <div class="mb-3">
                    <label for="manager" class="form-label">ENCARGADO:</label>
                    <select class="form-control js-select-manager" id="input-department-manager" name="manager" required data-show-subtext="true" data-live-search="true">
                        <option value="">Sin encargado</option>

                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        por favor, seleccione un encargado.
                    </div>
                </div>

               <!-- campo para la descripción -->
                <div class="mb-3">
                    <label for="description" class="form-label">DESCRIPCION:</label>
                    <textarea class="form-control" id="input-department-description" name="description" rows="3" required></textarea>
                    <div class="invalid-feedback">
                        por favor, ingrese una descripción.
                    </div>
                </div>

                <!-- campo para la latitud  -->
                <div class="mb-3">
                    <input type="text" id="latitude" name="latitude" placeholder="Latitud" class="form-control" readonly>
                </div>

                <!-- campo para la longitud -->
                <div class="mb-3">
                    <input type="text" id="longitude" name="longitude" placeholder="Longitud" class="form-control" readonly>
                </div>

                <!-- boton para guardar el nuevo departamento -->
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">GUARDAR</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="{{ asset('assets/js/departments/create.js') }}"></script>

@endsection
