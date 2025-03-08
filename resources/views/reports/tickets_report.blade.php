@extends('layouts.user_type.auth')

@section('content')
<div class="row mt-4">
    <!-- Columna izquierda: Filtros -->
    <div class="col-md-3">
        <div class="card p-3">
            <!-- Filtro de tipo de ticket -->
            <div id="ticketTypeFilterContainer" class="mb-3">
                <label for="ticketTypeFilter">Elige el tipo de ticket</label>
                <select id="ticketTypeFilter" class="form-select">
                    <option value="byPriority">Tickets por Prioridad</option>
                    <option value="byStatus">Tickets por Estado</option>
                    <option value="byAssignment">Tickets Asignados</option>
                    <option value="byTitle">Tickets por Título</option> <!-- Opción por título -->
                    <option value="byUser">Tickets por Usuario</option> <!-- Nueva opción por Usuario -->
                    <option value="byTechnician">Tickets por Técnico</option>
                </select>
            </div>

            <!-- Filtro por título de ticket -->
            <div class="mb-3" id="ticketTitleFilterContainer" style="display:none;">
                <label for="ticketTitleFilter">Filtrar por Título</label>
                <select id="ticketTitleFilter" name="ticketTitle" multiple class="form-control">
                    <option value="todos">Todos</option>
                    <option value="">Seleccione un título</option>
                    @foreach ($titles as $title)
                    <option value="{{ $title->id }}">{{ $title->name }}</option>
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
                ⚠️ Por favor, selecciona un tipo de ticket válido y completa los filtros necesarios.
            </div>

            <!-- Botones de acción -->
            <div class="d-grid gap-2">
                <button class="btn" style="background-color: #A52A2A; color: white;" id="generateTicketReportBtn">Consultar</button>
                <button class="btn" style="background-color: #8B0000; color: white;" onclick="generateTicketReport('pdf')">Generar PDF</button>
                <button class="btn" style="background-color: #B5651D; color: white;" onclick="generateTicketReport('excel')">Exportar a Excel</button>
            </div>
        </div>
    </div>

    <!-- Columna derecha: Resultados -->
    <div class="col-md-8">
        <div class="card p-3" id="ticketResult">
            <h2 class="mb-3" id="ticketReportTitle">Reporte de Tickets</h2>
            <div id="ticketResultContent">
                <p class="text-muted">Selecciona los filtros para generar el reporte de tickets.</p>
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
        $('#ticketTitleFilter').select2({
            placeholder: "Seleccione una opción",
            theme: "bootstrap-5",
            width: '100%',
        });
    };

    // Mostrar u ocultar el filtro por título según el tipo de filtro seleccionado
    document.getElementById('ticketTypeFilter').addEventListener('change', function() {
        const selectedType = this.value;
        const titleFilterContainer = document.getElementById('ticketTitleFilterContainer');

        if (selectedType === 'byTitle') {
            titleFilterContainer.style.display = 'block'; // Mostrar filtro de título
        } else {
            titleFilterContainer.style.display = 'none'; // Ocultar filtro de título
        }
    });

    // Función para manejar la consulta y los filtros de tickets
    document.getElementById('generateTicketReportBtn').addEventListener('click', function() {
        const ticketType = document.getElementById('ticketTypeFilter').value;
        const ticketTitle = document.getElementById('ticketTitleFilter').value; // Filtro por título
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const errorContainer = document.getElementById('errorContainer');

        // Validar que ambas fechas sean seleccionadas
        if (!startDate || !endDate) {
            errorContainer.textContent = '⚠️ Por favor, selecciona ambas fechas antes de continuar.';
            errorContainer.classList.remove('d-none');
            return;
        }

        // Validar que la fecha de inicio no sea mayor que la fecha final
        const start = new Date(startDate);
        const end = new Date(endDate);
        const today = new Date(); // Fecha actual

        // Eliminar horas, minutos y segundos para comparar solo fechas
        today.setHours(0, 0, 0, 0);
        start.setHours(0, 0, 0, 0);
        end.setHours(0, 0, 0, 0);

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

        // Obtener los datos del reporte de tickets con AJAX
        $.ajax({
            url: `/reportes/tickets/data`, // Cambia la URL según tu ruta
            type: 'GET',
            data: {
                startDate: startDate,
                endDate: endDate,
                ticketType: ticketType,
                ticketTitle: ticketTitle // Pasar el filtro de título
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            success: function(data) {
                console.log(data);
                const reportTitle = document.getElementById('ticketReportTitle');
                const resultContent = document.getElementById('ticketResultContent');
                reportTitle.textContent = 'Reporte de Tickets';

                // Construir el contenido del reporte de tickets en formato de tabla
                let content = '<table class="table table-bordered"><thead><tr>';

                // Ajustar columnas según el tipo de ticket
                if (ticketType === 'byPriority') {
                    content += `
                        <th>Prioridad</th>
                        <th>Cantidad de Tickets</th>
                    `;
                    data.data.forEach(priority => {
                        content += `
                            <tr>
                                <td>${priority.priority || 'No disponible'}</td>
                                <td>${priority.total_tickets}</td>
                            </tr>
                        `;
                    });
                } else if (ticketType === 'byStatus') {
                    content += `
                        <th>Estado</th>
                        <th>Cantidad de Tickets</th>
                    `;
                    data.data.forEach(status => {
                        content += `
                            <tr>
                                <td>${status.status || 'Estado desconocido'}</td>
                                <td>${status.total_tickets}</td>
                            </tr>
                        `;
                    });
                } else if (ticketType === 'byAssignment') {
                    content += `
                        <th>Asignado a</th>
                        <th>Cantidad de Tickets</th>
                    `;
                    data.data.forEach(assigned => {
                        content += `
                            <tr>
                                <td>${assigned.technician_name || 'Sin asignar'}</td>
                                <td>${assigned.total_tickets}</td>
                            </tr>
                        `;
                    });
                } else if (ticketType === 'byTitle') {
                    content += `
                        <th>Título</th>
                        <th>Total</th>
                    `;
                    data.data.forEach(ticket => {
                        content += `
                            <tr>
                                <td>${ticket.ticket_name || 'Sin título'}</td>
                                <td>${ticket.total_tickets}</td>
                            </tr>
                        `;
                    });
                } else if (ticketType === 'byUser') {
                    content += `
                        <th>Usuario</th>
                        <th>Cantidad de Tickets</th>
                    `;
                    data.data.forEach(user => {
                        content += `
                            <tr>
                                <td>${user.user_name || 'Usuario desconocido'}</td>
                                <td>${user.total_tickets}</td>
                            </tr>
                        `;
                    });
                } else if (ticketType === 'byTechnician') {
                    content += `
                        <th>Usuario</th>
                        <th>Cantidad de Tickets</th>
                    `;
                    data.data.forEach(technician => {
                        content += `
                            <tr>
                                <td>${technician.technician_name || 'Usuario desconocido'}</td>
                                <td>${technician.total_tickets}</td>
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
                console.error(error);
                errorContainer.classList.remove('d-none');
                errorContainer.textContent = '⚠️ Ocurrió un error al generar el reporte. Intenta nuevamente.';
            }
        });
    });

    // Función para generar el reporte PDF o Excel
    function generateTicketReport(format) {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const ticketType = document.getElementById('ticketTypeFilter').value;
        const ticketTitle = document.getElementById('ticketTitleFilter').value;

        // Aquí puedes agregar lógica para generar el reporte en el formato adecuado
    }
</script>
@endsection