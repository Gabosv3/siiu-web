@extends('layouts.user_type.auth')

@section('content')
<div class="row mt-4">
    <!-- Columna izquierda: Filtros -->
    <div class="col-md-3">
        <div class="card p-3">
            <!-- Filtro de tipo de insumo -->
            <div id="insumoTypeFilterContainer" class="mb-3">
                <label for="insumoTypeFilter">Elige el tipo de insumo</label>
                <select id="insumoTypeFilter" class="form-select">
                    <option value="byCategory">Insumos por Categoría</option>
                    <option value="byManufacturer">Insumos por Fabricante</option>
                    <option value="byModel">Insumos por Modelo</option>
                    <option value="byStatus">Insumos por Estado</option>
                </select>
            </div>

            <!-- Filtro de fechas -->
            <div class="mb-3">
                <label for="startDate">Fecha desde</label>
                <input type="date" id="startDate" class="form-control">
            </div>
            <div class="mb-3">
                <label for="endDate">Fecha hasta</label>
                <input type="date" id="endDate" class="form-control">
            </div>

            <!-- Contenedor para el mensaje de error -->
            <div id="errorContainer" class="text-danger small mt-2 d-none" style="font-size: 0.85rem;">
                ⚠️ Por favor, selecciona un tipo de insumo válido y completa los filtros necesarios.
            </div>

            <!-- Botones de acción -->
            <div class="d-grid gap-2">
                <button class="btn" style="background-color: #A52A2A; color: white;" id="generateInsumoReportBtn">Consultar</button>

            </div>
        </div>
    </div>

    <!-- Columna derecha: Resultados -->
    <div class="col-md-8">
        <div class="card p-3" id="insumoResult">
            <h2 class="mb-3" id="insumoReportTitle">Reporte de Insumos</h2>
            <div id="insumoResultContent">
                <p class="text-muted">Selecciona los filtros para generar el reporte de insumos.</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Establecer las fechas predeterminadas
    window.onload = function() {
        const today = new Date();
        const threeDaysAgo = new Date();
        threeDaysAgo.setDate(today.getDate() - 3);

        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        document.getElementById('startDate').value = formatDate(threeDaysAgo);
        document.getElementById('endDate').value = formatDate(today);


    };

    // Mostrar u ocultar el filtro de usuarios dependiendo del tipo de insumo
    document.getElementById('insumoTypeFilter').addEventListener('change', function() {
        const insumoType = this.value;
        const userFilterContainer = document.getElementById('userFilterContainer');

        if (insumoType === 'byLocation') {
            userFilterContainer.style.display = 'block';
        } else {
            userFilterContainer.style.display = 'none';
        }
    });

    // Función para manejar la consulta y los filtros de insumos
    document.getElementById('generateInsumoReportBtn').addEventListener('click', function() {
        const insumoType = document.getElementById('insumoTypeFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const errorContainer = document.getElementById('errorContainer');

        // Convertir fechas a objetos Date
        const start = new Date(startDate);
        const end = new Date(endDate);
        const today = new Date(); // Fecha actual

        // Eliminar horas, minutos y segundos para comparar solo fechas
        today.setHours(0, 0, 0, 0);
        start.setHours(0, 0, 0, 0);
        end.setHours(0, 0, 0, 0);

        // Validar que ambas fechas sean seleccionadas
        if (!startDate || !endDate) {
            errorContainer.textContent = '⚠️ Por favor, selecciona ambas fechas antes de continuar.';
            errorContainer.classList.remove('d-none');
            return;
        }

        // Validar que la fecha de inicio no sea mayor que la fecha final
        if (start > end) {
            errorContainer.textContent = '⚠️ La fecha de inicio no puede ser mayor que la fecha de finalización.';
            errorContainer.classList.remove('d-none');
            return;
        }

        // Validar que ninguna de las fechas sea mayor que la actual
        if (start > today || end > today) {
            errorContainer.textContent = '⚠️ Ninguna de las fechas puede ser mayor que la fecha actual.';
            errorContainer.classList.remove('d-none');
            return;
        }

        // Ocultar el mensaje de error
        errorContainer.classList.add('d-none');

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Obtener los datos del reporte de insumos con AJAX
        $.ajax({
            url: `/reportes/insumos/data`, // Cambia la URL según tu ruta
            type: 'GET',
            data: {
                startDate: startDate,
                endDate: endDate,
                insumoType: insumoType
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            success: function(data) {
                console.log(data);
                const reportTitle = document.getElementById('insumoReportTitle');
                const resultContent = document.getElementById('insumoResultContent');
                reportTitle.textContent = 'Reporte de Insumos';

                // Construir el contenido del reporte de insumos en formato de tabla
                let content = `
                    <table id="insumoTable" class="table table-bordered display nowrap">
                        <thead>
                            <tr>
                                <th>Insumo</th>
                                <th>${insumoType === 'byCategory' ? 'Categoría' :
                                    insumoType === 'byManufacturer' ? 'Fabricante' :
                                    insumoType === 'byModel' ? 'Modelo' : 'Estado'}</th>
                                <th>Cantidad de Insumos</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.data.forEach(item => {
                    content += `
                        <tr>
                            <td>${item.supply_name || item.name}</td>
                            <td>${item.category_name || item.manufacturer_name || item.model_name || item.status}</td>
                            <td>${item.total_quantity || item.quantity}</td>
                        </tr>
                    `;
                });

                content += `</tbody></table>`;

                // Mostrar el contenido en la vista
                resultContent.innerHTML = content;
                // Inicializar DataTables con exportación
                $('#insumoTable').DataTable({
                    dom: 'Bfrtip',
                    paging: false, // Deshabilita la paginación
                    searching: false, // Deshabilita el buscador
                    info: false, // Oculta la información de registros ("Mostrando X de Y")
                    ordering: false, // Deshabilita la ordenación de columnas
                    buttons: [{
                            extend: 'excelHtml5',
                            text: 'Exportar a Excel',
                            messageTop: 'Reporte de Insumos',
                            className: 'btn btn-green-600'
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'Exportar a PDF',
                            messageTop: 'Reporte de Insumos',
                            className: 'btn btn-cyan-800'
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            messageTop: 'Reporte de Insumos',
                            className: 'btn btn-red-800'
                        }
                    ],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/Spanish.json"
                    }
                });

                errorContainer.classList.add('d-none');
            },
            error: function(error) {
                console.error('Error al generar el reporte de insumos:', error);

                // Mostrar un mensaje de error
                const errorContainer = document.getElementById('errorContainer');
                errorContainer.textContent = `⚠️ Ocurrió un error: ${error.responseText}`;
                errorContainer.classList.remove('d-none');

                // Limpiar el contenido del reporte
                const resultContent = document.getElementById('insumoResultContent');
                resultContent.innerHTML =
                    '<p class="text-muted">No se pudo generar el reporte. Inténtalo de nuevo.</p>';
            }
        });
    });

    // Función para generar reportes de insumos en PDF y Excel
    function generateInsumoReport(type) {
        const insumoType = document.getElementById('insumoTypeFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: `/api/generar-reporte-insumos`, // Ruta para generar PDF o Excel
            type: 'POST',
            data: {
                insumoType: insumoType,
                startDate: startDate,
                endDate: endDate,
                type: type
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            success: function(response) {
                window.location.href = response.url; // Redirigir a la URL de descarga
            },
            error: function(error) {
                console.error('Error al generar el archivo:', error);
            }
        });
    }
</script>
@endsection