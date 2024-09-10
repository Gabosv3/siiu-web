@extends('layouts.user_type.auth')

@section('content')
<div class="card mt-4 p-3">
    <h1 class="text-center mb-5">Crear Departamento</h1>
    <div class="row">
        <!-- Columna para el mapa -->
        <div class="col-md-6 map-container">
            <div id="map"></div>
        </div>

        <!-- Columna para el formulario -->
        <div class="col-md-6">
            <form action="{{ route('departamentos.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <!-- Campo para el nombre del departamento -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="input-nombre-departamento" name="nombre" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" required>
                    <div class="invalid-feedback">
                        Por favor, ingrese un nombre válido (solo letras y espacios).
                    </div>
                </div>

                <!-- Campo para el código del departamento -->
                <div class="mb-3">
                    <label for="codigo" class="form-label">Código:</label>
                    <input type="text" class="form-control" id="input-codigo-departamento" name="codigo" pattern="\d+" required>
                    <div class="invalid-feedback">
                        Por favor, ingrese un código válido (solo números).
                    </div>
                </div>

                <!-- Campo para el encargado del departamento -->
                <div class="mb-3">
                    <label for="encargado" class="form-label">Encargado:</label>
                    <select class="form-control js-example-basic-single" id="input-encargado-departamento" name="encargado" data-show-subtext="true" data-live-search="true">
                        <option value="">Sin encargado</option> <!-- Opción para no seleccionar ningún encargado -->

                        @foreach ($users as $user)
                        @if ($user->informacionPersonal && $user->informacionPersonal->nombres && $user->informacionPersonal->apellidos)
                        <option value="{{ $user->id }}">
                            {{ $user->informacionPersonal->nombres }} {{ $user->informacionPersonal->apellidos }}
                        </option>
                        @endif
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        Por favor, seleccione un encargado válido.
                    </div>
                </div>

                <!-- Campo para la descripción del departamento -->
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" id="input-descripcion-departamento" name="descripcion" rows="3"></textarea>
                </div>

                <!-- Campo para ingresar la latitud -->
                <div class="mb-3">
                    <input type="text" id="latitude" name="latitude" placeholder="Latitud" class="form-control" readonly >
                </div>

                <!-- Campo para ingresar la longitud -->
                <div class="mb-3">
                    <input type="text" id="longitude" name="longitude" placeholder="Longitud" class="form-control" readonly>
                </div>

                <!-- Botón para guardar el nuevo departamento -->
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener el nombre del departamento del campo de entrada
        var departamentoNombre = document.getElementById('input-nombre-departamento').value;

        // Inicializar el mapa y establecer la vista en una ubicación por defecto
        var defaultLat = parseFloat(document.getElementById('latitude').value) || 13.43931902478275;
        var defaultLng = parseFloat(document.getElementById('longitude').value) || -88.15837383270265;
        var map = L.map('map').setView([defaultLat, defaultLng], 17);

        // Agregar las capas de mapa base
        var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18,
            attribution: '© <a href="https://www.esri.com/en-us/arcgis/about-arcgis/overview">Esri</a>'
        });

        // Crear un grupo de capas y añadir las capas al grupo
        var baseMaps = {
            "Mapa": osmLayer,
            "Satelital": satelliteLayer
        };

        // Agregar el control de capas al mapa
        L.control.layers(baseMaps).addTo(map);

        // Crear y añadir el marcador inicial si hay latitud y longitud
        var marker = L.marker([defaultLat, defaultLng]).addTo(map)
            .bindTooltip(departamentoNombre, {
                permanent: true,
                direction: 'top'
            })
            .openTooltip();

        // Actualizar los campos de latitud y longitud cuando se haga clic en el mapa
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            // Mover el marcador a la nueva ubicación
            marker.setLatLng([lat, lng]).bindTooltip(departamentoNombre, {
                permanent: true,
                direction: 'top'
            }).openTooltip();

            // Actualizar los campos de latitud y longitud
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });
    });
</script>
<script>
    // Espera a que el DOM esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('.needs-validation');

        // Añade un evento 'submit' al formulario para la validación
        form.addEventListener('submit', function(event) {
            // Realiza la validación
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);

        // Validación en tiempo real
        form.addEventListener('input', function(event) {
            var input = event.target;

            // Validación para el campo de nombre
            if (input.id === 'input-nombre-departamento') {
                var pattern = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;
                if (pattern.test(input.value)) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            }

            // Validación para el campo de código
            if (input.id === 'input-codigo-departamento') {
                var pattern = /^\d+$/;
                if (pattern.test(input.value)) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            }

            // Validación para el campo de latitud
            if (input.id === 'latitude') {
                var pattern = /^-?\d+(\.\d+)?$/;
                if (pattern.test(input.value)) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            }

            // Validación para el campo de longitud
            if (input.id === 'longitude') {
                var pattern = /^-?\d+(\.\d+)?$/;
                if (pattern.test(input.value)) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            }
        }, true);
    }, false);
</script>

@endsection