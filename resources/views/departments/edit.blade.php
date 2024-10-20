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
            <form action="{{ route('departaments.update', $departament->id) }}" method="POST" class="needs-validation" novalidate>
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

                <div class="mb-3">
                    <label for="manager" class="form-label">ENCARGADO:</label>
                    <select class="form-control js-select-manager" id="input-department-manager" name="manager" required>
                        <option value="">Sin encargado</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $departament->manager == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} {{ $user->personalInformation->first_name }} {{ $user->personalInformation->last_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        por favor, seleccione un encargado.
                    </div>
                </div>

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

        var defaultLat = parseFloat(document.getElementById('latitude').value) || 13.43931902478275;
        var defaultLng = parseFloat(document.getElementById('longitude').value) || -88.15837383270265;
        var map = L.map('map').setView([defaultLat, defaultLng], 17);

        var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18,
            attribution: '© <a href="https://www.esri.com/en-us/arcgis/about-arcgis/overview">Esri</a>'
        });

        var baseMaps = {
            "Map": osmLayer,
            "Satellite": satelliteLayer
        };

        L.control.layers(baseMaps).addTo(map);

        var marker = L.marker([defaultLat, defaultLng]).addTo(map)
            .bindTooltip(departmentName, {
                permanent: true,
                direction: 'top'
            })
            .openTooltip();

        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            marker.setLatLng([lat, lng]).bindTooltip(departmentName, {
                permanent: true,
                direction: 'top'
            }).openTooltip();
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });
    });
</script>
@endsection
