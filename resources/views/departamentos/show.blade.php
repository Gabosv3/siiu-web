@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Detalles del Departamento</h1>
    <div class="row">
        <!-- Columna para el mapa -->
        <div class="col-md-6 map-container">
            <div id="map"></div>
        </div>

        <!-- Columna para los detalles del departamento -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $departamento->nombre }}</h2>
                </div>
                <div class="card-body">
                    <p><strong>Código:</strong> {{ $departamento->codigo }}</p>
                    <p><strong>Encargado:</strong> {{ $departamento->encargado  ?? 'Sin encargado'}}</p>
                    <p><strong>Descripción:</strong> {{ $departamento->descripcion ?? 'No disponible' }}</p>
                    <p><strong>Latitud:</strong> {{ $departamento->latitude }}</p>
                    <p><strong>Longitud:</strong> {{ $departamento->longitude }}</p>
                    <p><strong>Fecha de Creación:</strong> {{ $departamento->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Fecha de Actualización:</strong> {{ $departamento->updated_at->format('d/m/Y H:i') }}</p>
                    <div class="col-md-12 text-end">
                        <a href="{{ route('departamentos.index') }}" class="btn btn-primary">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts para inicializar el mapa -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el mapa y establecer la vista en la ubicación del departamento
        var departamentoNombre = "{{ $departamento->nombre }}";
        var latitude = parseFloat("{{ $departamento->latitude }}") || 13.43931902478275;
        var longitude = parseFloat("{{ $departamento->longitude }}") || -88.15837383270265;
        var map = L.map('map').setView([latitude, longitude], 17);

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

        // Crear y añadir el marcador en la ubicación del departamento
        var marker = L.marker([latitude, longitude]).addTo(map)
            .bindTooltip(departamentoNombre, {
                permanent: true,
                direction: 'top'
            })
            .openTooltip();
    });
</script>
@endsection