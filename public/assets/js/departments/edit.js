$(document).ready(function() {
    $('.js-select-manager').select2({
        placeholder: "Seleccione un encargado",
        theme: "bootstrap-5",
        width: '100%',
    });
});

document.addEventListener('DOMContentLoaded', function() {
    let departmentName = document.getElementById('input-department-name').value;

    let defaultLat = parseFloat(document.getElementById('latitude').value) || 13.43931902478275;
    let defaultLng = parseFloat(document.getElementById('longitude').value) || -88.15837383270265;
    let map = L.map('map').setView([defaultLat, defaultLng], 17);

    let osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    let satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 18,
        attribution: '© <a href="https://www.esri.com/en-us/arcgis/about-arcgis/overview">Esri</a>'
    });

    let baseMaps = {
        "Map": osmLayer,
        "Satellite": satelliteLayer
    };

    L.control.layers(baseMaps).addTo(map);

    let marker = L.marker([defaultLat, defaultLng]).addTo(map)
        .bindTooltip(departmentName, {
            permanent: true,
            direction: 'top'
        })
        .openTooltip();

    map.on('click', function(e) {
        let lat = e.latlng.lat;
        let lng = e.latlng.lng;
        marker.setLatLng([lat, lng]).bindTooltip(departmentName, {
            permanent: true,
            direction: 'top'
        }).openTooltip();
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    });
});