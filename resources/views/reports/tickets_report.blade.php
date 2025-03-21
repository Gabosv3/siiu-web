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
                const reportTitle = document.getElementById('ticketReportTitle');
                const resultContent = document.getElementById('ticketResultContent');
                reportTitle.textContent = 'Reporte de Tickets';

                let content = `
                    <table id="ticketTable" class="table table-bordered display nowrap">
                        <thead>
                            <tr>
                                <th>${ticketType === 'byPriority' ? 'Prioridad' :
                                    ticketType === 'byStatus' ? 'Estado' :
                                    ticketType === 'byAssignment' ? 'Asignado a' :
                                    ticketType === 'byTitle' ? 'Título' :
                                    ticketType === 'byUser' ? 'Usuario' :
                                    'Técnico'}</th>
                                <th>Cantidad de Tickets</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                                data.data.forEach(item => {
                                    content += `
                        <tr>
                            <td>${item.priority || item.status || item.technician_name || item.ticket_name || item.user_name || 'Desconocido'}</td>
                            <td>${item.total_tickets}</td>
                        </tr>
                    `;
                });

                content += `</tbody></table>`;

                // Mostrar el contenido en la vista
                resultContent.innerHTML = content;
                errorContainer.classList.add('d-none');

                // Inicializar DataTables con botones de exportación y sin buscador ni paginación
                $(document).ready(function() {
                    $('#ticketTable').DataTable({
                        dom: 'Bfrtip',
                        paging: false,
                        searching: false,
                        info: false,
                        ordering: false,
                        buttons: [{
                                extend: 'excelHtml5',
                                text: 'Exportar a Excel',
                                messageTop: 'Reporte de Tickets',
                                className: 'btn btn-green-600'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'Exportar a PDF',
                                messageTop: 'Reporte de Tickets',
                                className: 'btn btn-cyan-800'
                            },
                            {
                                extend: 'print',
                                text: 'Imprimir',
                                messageTop: 'Reporte de Tickets',
                                className: 'btn btn-red-800'
                            }
                        ],
                        language: {
                            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/Spanish.json"
                        }
                    });
                });

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