@extends('layouts.user_type.auth')

@section('content')
<div class="row mt-4">
    <!-- Columna izquierda: Filtros -->
    <div class="col-md-3">
        <div class="card p-3">
            <!-- Filtro de tipo de reporte -->
            <div id="reportTypeFilterContainer" class="mb-3">
                <label for="reportTypeFilter">Elige el tipo de reporte</label>
                <select id="reportTypeFilter" class="form-select">
                    <option value="usersByDepartment">Usuarios por Departamento</option>
                    <option value="ticketsByUser">Tickets por Usuario</option>
                    <option value="hardwareByUser">Equipos Asignados por Usuario</option>
                </select>
            </div>

            <!-- Filtro de usuarios (solo visible para los reportes de Tickets por Usuario y Equipos Asignados por Usuario) -->
            <div id="userFilterContainer" class="mb-3" style="display: none;">
                <label for="userFilter">Elige el usuario</label>
                <select id="userFilter" class="form-select" multiple>
                    <option value="todos">Todos</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
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
                ⚠️ Por favor, selecciona un tipo de reporte válido y completa los filtros necesarios.
            </div>

            <!-- Botones de acción -->
            <div class="d-grid gap-2">
                <button class="btn" style="background-color: #A52A2A; color: white;"
                    id="generateReportBtn">Consultar</button>
                <button class="btn" style="background-color: #8B0000; color: white;"
                    onclick="generateReport('pdf')">Generar PDF</button>
                <button class="btn" style="background-color: #B5651D; color: white;"
                    onclick="generateReport('excel')">Exportar a Excel</button>
            </div>
        </div>
    </div>

    <!-- Columna derecha: Resultados -->
    <div class="col-md-8">
        <div class="card p-3" id="reportResult">
            <h2 class="mb-3" id="reportTitle">Reportes por Usuario</h2>
            <div id="resultContent">
                <p class="text-muted">Selecciona un usuario, un departamento o un rango de fechas para generar el
                    reporte.</p>
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

        // Inicializar Select2
        $('#userFilter').select2({
            placeholder: "Seleccione una opción",
            theme: "bootstrap-5",
            width: '100%',
        });
    };

    // Mostrar u ocultar el filtro de usuarios dependiendo del tipo de reporte
    document.getElementById('reportTypeFilter').addEventListener('change', function() {
        const reportType = this.value;
        const userFilterContainer = document.getElementById('userFilterContainer');

        if (reportType === 'ticketsByUser' || reportType === 'hardwareByUser') {
            userFilterContainer.style.display = 'block';
        } else {
            userFilterContainer.style.display = 'none';
        }
    });

    // Función para manejar la consulta y los filtros
    document.getElementById('generateReportBtn').addEventListener('click', function() {
        const reportType = document.getElementById('reportTypeFilter').value;
        const userIds = Array.from(document.getElementById('userFilter').selectedOptions).map(option => option
            .value);
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

        // Obtener los datos del reporte con AJAX
        $.ajax({
            url: `/reportes/datausuario`,
            type: 'GET',
            data: {
                userIds: userIds.length === 0 || userIds.includes('todos') ? [] : userIds,
                startDate: startDate,
                endDate: endDate,
                reportType: reportType

            },
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            success: function(data) {
                const reportTitle = document.getElementById('reportTitle');
                const resultContent = document.getElementById('resultContent');
                reportTitle.textContent = 'Reporte de ' + reportType;

                // Construir el contenido del reporte en formato de tabla
                let content = '<table class="table table-bordered"><thead><tr>';

                if (reportType === 'usersByDepartment') {
                    content += `
                        <th>Departamento</th>
                        <th>Usuarios</th>
                    `;
                    data.data.forEach(department => {
                        content += `
                            <tr>
                                <td>${department.name}</td>
                                <td>${department.users_count}</td>
                            </tr>
                        `;
                    });
                } else if (reportType === 'ticketsByUser') {
                    content += `
                        <th>Usuario</th>
                        <th>Tickets Generados</th>
                    `;
                    data.data.forEach(user => {
                        content += `
                            <tr>
                                <td>${user.name}</td>
                                <td>${user.tickets_count}</td>
                            </tr>
                        `;
                    });
                } else if (reportType === 'hardwareByUser') {
                    content += `
                        <th>Usuario</th>
                        <th>Equipos Asignados</th>
                    `;
                    data.data.forEach(user => {
                        content += `
                            <tr>
                                <td>${user.name}</td>
                                <td>${user.hardware_count}</td>
                            </tr>
                        `;
                    });
                }

                content += '</tr></thead><tbody></tbody></table>';

                // Mostrar el contenido en la vista
                resultContent.innerHTML = content;
                errorContainer.classList.add('d-none');
            },
            error: function(error) {
                console.error('Error al generar el reporte:', error);

                // Mostrar un mensaje de error
                const errorContainer = document.getElementById('errorContainer');
                errorContainer.textContent = `⚠️ Ocurrió un error: ${error.responseText}`;
                errorContainer.classList.remove('d-none');

                // Limpiar el contenido del reporte
                const resultContent = document.getElementById('resultContent');
                resultContent.innerHTML =
                    '<p class="text-muted">No se pudo generar el reporte. Inténtalo de nuevo.</p>';
            }
        });
    });

    // Función para generar reportes en PDF y Excel
    function generateReport(type) {
        const reportType = document.getElementById('reportTypeFilter').value;
        const userIds = Array.from(document.getElementById('userFilter').selectedOptions).map(option => option.value);
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: `/api/generar-reporte`,
            type: 'POST',
            data: {

                reportType: reportType,
                userIds: userIds.length === 0 || userIds.includes('todos') ? [] : userIds,
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