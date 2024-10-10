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
                            {{$user->name}}{{ $user->personalInformation->first_name }} {{ $user->personalInformation->last_name }}
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

<script>
    $(document).ready(function() {
        $('.js-select-manager').select2({
            placeholder: "Seleccione un encargado",
            theme: "bootstrap-5",
            width: '100%',
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        var departmentName = document.getElementById('input-department-name').value;

        // INICIALIZAR EL MAPA Y ESTABLECER LA VISTA EN UNA UBICACIÓN POR DEFECTO
        var defaultLat = parseFloat(document.getElementById('latitude').value) || 13.43931902478275;
        var defaultLng = parseFloat(document.getElementById('longitude').value) || -88.15837383270265;
        var map = L.map('map').setView([defaultLat, defaultLng], 17);

        // AGREGAR LAS CAPAS DE MAPA BASE
        var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18,
            attribution: '© <a href="https://www.esri.com/en-us/arcgis/about-arcgis/overview">Esri</a>'
        });

        // CREAR UN GRUPO DE CAPAS Y AGREGARLAS AL GRUPO
        var baseMaps = {
            "Map": osmLayer,
            "Satellite": satelliteLayer
        };

        // AGREGAR EL GRUPO DE CAPAS AL MAPA
        L.control.layers(baseMaps).addTo(map);

        // CREAR UNA MARCA EN EL MAPA CON LA UBICACIÓN POR DEFECTO
        var marker = L.marker([defaultLat, defaultLng]).addTo(map)
            .bindTooltip(departmentName, {
                permanent: true,
                direction: 'top'
            })
            .openTooltip();

        // ACTUALIZAR LOS CAMPOS DE LATITUD Y LONGITUD AL HACER CLIC EN EL MAPA
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            // MOVER LA MARCA A LA NUEVA UBICACIÓN
            marker.setLatLng([lat, lng]).bindTooltip(departmentName, {
                permanent: true,
                direction: 'top'
            }).openTooltip();

            // ACTUALIZAR LOS CAMPOS DE LATITUD Y LONGITUD
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });
    });
</script>
<script>
    // UTILIZAR EL FORMULARIO DE VALIDACIÓN
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('.needs-validation');

        // AGREGAR UN EVENTO 'SUBMIT' AL FORMULARIO
        form.addEventListener('submit', function(event) {
            // VALIDAR EL FORMULARIO
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);

        // REALIZAR LA VALIDACIÓN EN TIEMPO REAL
        form.addEventListener('input', function(event) {
            var input = event.target;

            // VALIDAR EL CAMPO DE NOMBRE
            if (input.id === 'input-department-name') {
                var pattern = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;
                if (pattern.test(input.value)) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            }

            // VALIDAR EL CAMPO DE CODIGO
            if (input.id === 'input-department-code') {
                var pattern = /^\d+$/;
                if (pattern.test(input.value)) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            }

            // VALIDAR EL CAMPO DE LATITUD
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

            // VALIDAR EL CAMPO DE LONGITUD
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
