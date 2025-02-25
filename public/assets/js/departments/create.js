$(document).ready(function () {
    $(".js-select-manager").select2({
        placeholder: "Seleccione un encargado",
        theme: "bootstrap-5",
        width: "100%",
    });
});

document.addEventListener("DOMContentLoaded", function () {
    let departmentName = document.getElementById("input-department-name").value;

    // INICIALIZAR EL MAPA Y ESTABLECER LA VISTA EN UNA UBICACIÓN POR DEFECTO
    let defaultLat =
        parseFloat(document.getElementById("latitude").value) ||
        13.43931902478275;
    let defaultLng =
        parseFloat(document.getElementById("longitude").value) ||
        -88.15837383270265;
    let map = L.map("map").setView([defaultLat, defaultLng], 17);

    // AGREGAR LAS CAPAS DE MAPA BASE
    let osmLayer = L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
            maxZoom: 19,
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }
    ).addTo(map);

    let satelliteLayer = L.tileLayer(
        "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
        {
            maxZoom: 18,
            attribution:
                '© <a href="https://www.esri.com/en-us/arcgis/about-arcgis/overview">Esri</a>',
        }
    );

    // CREAR UN GRUPO DE CAPAS Y AGREGARLAS AL GRUPO
    let baseMaps = {
        Map: osmLayer,
        Satellite: satelliteLayer,
    };

    // AGREGAR EL GRUPO DE CAPAS AL MAPA
    L.control.layers(baseMaps).addTo(map);

    // CREAR UNA MARCA EN EL MAPA CON LA UBICACIÓN POR DEFECTO
    let marker = L.marker([defaultLat, defaultLng])
        .addTo(map)
        .bindTooltip(departmentName, {
            permanent: true,
            direction: "top",
        })
        .openTooltip();

    // ACTUALIZAR LOS CAMPOS DE LATITUD Y LONGITUD AL HACER CLIC EN EL MAPA
    map.on("click", function (e) {
        let lat = e.latlng.lat;
        let lng = e.latlng.lng;

        // MOVER LA MARCA A LA NUEVA UBICACIÓN
        marker
            .setLatLng([lat, lng])
            .bindTooltip(departmentName, {
                permanent: true,
                direction: "top",
            })
            .openTooltip();

        // ACTUALIZAR LOS CAMPOS DE LATITUD Y LONGITUD
        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;
    });
});

// UTILIZAR EL FORMULARIO DE VALIDACIÓN
document.addEventListener(
    "DOMContentLoaded",
    function () {
        let form = document.querySelector(".needs-validation");

        // AGREGAR UN EVENTO 'SUBMIT' AL FORMULARIO
        form.addEventListener(
            "submit",
            function (event) {
                // VALIDAR EL FORMULARIO
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add("was-validated");
            },
            false
        );

        // REALIZAR LA VALIDACIÓN EN TIEMPO REAL
        form.addEventListener(
            "input",
            function (event) {
                let input = event.target;

                // VALIDAR EL CAMPO DE NOMBRE
                if (input.id === "input-department-name") {
                    let pattern = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;
                    if (pattern.test(input.value)) {
                        input.classList.remove("is-invalid");
                        input.classList.add("is-valid");
                    } else {
                        input.classList.remove("is-valid");
                        input.classList.add("is-invalid");
                    }
                }

                // VALIDAR EL CAMPO DE CODIGO
                if (input.id === "input-department-code") {
                    let pattern = /^\d+$/;
                    if (pattern.test(input.value)) {
                        input.classList.remove("is-invalid");
                        input.classList.add("is-valid");
                    } else {
                        input.classList.remove("is-valid");
                        input.classList.add("is-invalid");
                    }
                }

                // VALIDAR EL CAMPO DE LATITUD
                if (input.id === "latitude") {
                    let pattern = /^-?\d+(\.\d+)?$/;
                    if (pattern.test(input.value)) {
                        input.classList.remove("is-invalid");
                        input.classList.add("is-valid");
                    } else {
                        input.classList.remove("is-valid");
                        input.classList.add("is-invalid");
                    }
                }

                // VALIDAR EL CAMPO DE LONGITUD
                if (input.id === "longitude") {
                    let pattern = /^-?\d+(\.\d+)?$/;
                    if (pattern.test(input.value)) {
                        input.classList.remove("is-invalid");
                        input.classList.add("is-valid");
                    } else {
                        input.classList.remove("is-valid");
                        input.classList.add("is-invalid");
                    }
                }
            },
            true
        );
    },
    false
);
