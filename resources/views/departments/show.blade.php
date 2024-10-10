@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Department Details</h1>
    <div class="row">
        <!-- Column for the map -->
        <div class="col-md-6 map-container">
            <div id="map"></div>
        </div>

        <!-- Column for the department details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $departament->name }}</h2>
                </div>
                <div class="card-body">
                    <p><strong>CODIGO:</strong> {{ $departament->code }}</p>
                    <p><strong>ENCARGADO:</strong> {{ $departament->manager ?? 'No manager assigned' }}</p>
                    <p><strong>DESCRIPCION:</strong> {{ $departament->description ?? 'Not available' }}</p>
                    <p><strong>Latitud:</strong> {{ $departament->latitude }}</p>
                    <p><strong>Longitud:</strong> {{ $departament->longitude }}</p>
                    <p><strong>FECHA DE CREACION:</strong> {{ $departament->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>FECHA DE ACTUALIZACION:</strong> {{ $departament->updated_at->format('d/m/Y H:i') }}</p>
                    <div class="col-md-12 text-end">
                        <a href="{{ route('departaments.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts to initialize the map -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the map and set the view to the department's location
        var departmentName = "{{ $departament->name }}";
        var latitude = parseFloat("{{ $departament->latitude }}") || 13.43931902478275;
        var longitude = parseFloat("{{ $departament->longitude }}") || -88.15837383270265;
        var map = L.map('map').setView([latitude, longitude], 17);

        // Add base map layers
        var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18,
            attribution: '© <a href="https://www.esri.com/en-us/arcgis/about-arcgis/overview">Esri</a>'
        });

        // Create a layer group and add the layers to the group
        var baseMaps = {
            "Map": osmLayer,
            "Satellite": satelliteLayer
        };

        // Add layer control to the map
        L.control.layers(baseMaps).addTo(map);

        // Create and add a marker at the department's location
        var marker = L.marker([latitude, longitude]).addTo(map)
            .bindTooltip(departmentName, {
                permanent: true,
                direction: 'top'
            })
            .openTooltip();
    });
</script>
@endsection
