@extends('layouts.user_type.auth')

@section('content')
<div class="row mt-4">
    <!-- Columna izquierda: Formulario que serían los filtros -->
    <div class="col-md-3">
        <div class="card p-3">
            <div class="mb-3">
                <label for="reportType">Elige el tipo de reporte</label>
                <select id="reportType" class="form-select" onchange="handleReportTypeChange()">
                    <option value="1">Seleccione</option>
                    <option value="tickets">Reporte de Tickets</option>
                    <option value="asignaciones">Reporte de Asignaciones</option>
                    <option value="equipos">Reporte de Equipos</option>
                    <option value="mantenimiento">Reporte de Historial </option>
                </select>
            </div>

            <!-- Filtro de usuarios no se ocupo al final-->
            <div id="userFilterContainer" class="mb-3">
                <label for="userFilter">Elige el usuario</label>
                <select id="userFilter" class="form-select">
                    <option value="todos">Todos</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Opciones dinámicas para "asignaciones" -->
            <div id="assignmentOptions" class="d-none">
                <!-- Select para técnicos -->
                <div class="mb-3">
                    <label for="technician">Técnico</label>
                    <select id="technician" class="form-select">
                        <option value="todos">Todos</option>
                        @foreach ($technicians as $technician)
                            <option value="{{ $technician->id }}">{{ $technician->user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Select para departamentos -->
                <div class="mb-3">
                    <label for="department">Departamento</label>
                    <select id="department" class="form-select">
                        <option value="todos">Todos</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Opciones dinámicas para "tickets" -->
            <div id="ticketOptions" class="d-none">
                <!-- Select para técnicos -->
                <div class="mb-3">
                    <label for="technician">Técnico</label>
                    <select id="technician" class="form-select">
                        <option value="todos">Todos</option>
                        @foreach ($technicians as $technician)
                            <option value="{{ $technician->id }}">{{ $technician->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="ticketStatus">Estado del ticket</label>
                    <select id="ticketStatus" class="form-select">
                        <option value="abierto">Abierto</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="resuelto">Resuelto</option>
                        <option value="cerrado">Cerrado</option>
                    </select>
                </div>
            </div>
            <!-- Filtro para "equipos" -->
            <div id="hardwareOptions" class="d-none">
                <div class="mb-3">
                    <label for="hardware">Selecciona el equipo</label>
                    <select id="hardware" class="form-select">
                        <option value="todos">Todos</option>
                        @foreach ($hardware as $equip)
                            <option value="{{ $equip->id }}">{{ $equip->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="hardwareStatus">Estado del equipo</label>
                    <select id="hardwareStatus" class="form-select">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
             <!-- Filtro para "historial" -->
             <div id="historyOptions" class="d-none">
                <div class="mb-3">
                    <label for="hardware">Selecciona el equipo</label>
                    <select id="hardware" class="form-select">
                        <option value="todos">Todos</option>
                        @foreach ($hardware as $equip)
                            <option value="{{ $equip->id }}">{{ $equip->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- Fecha desde -->
            <div class="mb-3">
                <label for="startDate">Fecha desde</label>
                <input type="date" id="startDate" class="form-control">
            </div>

            <!-- Fecha hasta -->
            <div class="mb-3">
                <label for="endDate">Fecha hasta</label>
                <input type="date" id="endDate" class="form-control">
            </div>

            <!-- Contenedor para el mensaje de error -->
            <div id="errorContainer" class="text-danger small mt-2 d-none" style="font-size: 0.85rem;">
                ⚠️ Por favor, selecciona un tipo de reporte válido y completa los filtros necesarios.
            </div>

            <!-- Botones -->
            <div class="d-grid gap-2">
                <button class="btn" style="background-color: #A52A2A; color: white;" onclick="generateReport('consultar')">Consultar</button>
                <button class="btn" style="background-color: #8B0000; color: white;" onclick="generateReport('pdf')">Generar PDF</button>
                <button class="btn" style="background-color: #B5651D; color: white;" onclick="generateReport('excel')">Exportar a Excel</button>
            </div>
        </div>
    </div>

    <!-- Columna derecha: que serían los resultados -->
    <div class="col-md-8">
        <div class="card p-3" id="reportResult">
            <h2 class="mb-3" id="reportTitle">Bienvenid@</h2>
            <div id="resultContent">
                <p class="text-muted">
                    Aquí se podrán visualizar los reportes en caso de no querer descargarlos.
                    También se puede explicar sobre cada tipo de reporte aquí.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    // Mostrar u ocultar opciones adicionales según el tipo de reporte
    function handleReportTypeChange() {
        const reportType = document.getElementById('reportType').value;
        const ticketOptions = document.getElementById('ticketOptions');
        const assignmentOptions = document.getElementById('assignmentOptions');
        const hardwareOptions = document.getElementById('hardwareOptions');
        const historyOptions = document.getElementById('historyOptions');
        const userFilterContainer = document.getElementById('userFilterContainer');

        // Ocultar todos los filtros dinámicos al cambiar
        ticketOptions.classList.add('d-none');
        assignmentOptions.classList.add('d-none');
        hardwareOptions.classList.add('d-none');
        historyOptions.classList.add('d-none');
        userFilterContainer.classList.add('d-none');

        // Mostrar filtros según el tipo de reporte seleccionado
        if (reportType === 'tickets') {
            ticketOptions.classList.remove('d-none');
        } else if (reportType === 'asignaciones') {
            assignmentOptions.classList.remove('d-none');
        } else if (reportType === 'equipos') {
            hardwareOptions.classList.remove('d-none');
        } else if (reportType === 'mantenimiento') {
            historyOptions.classList.remove('d-none');
        } else {
            userFilterContainer.classList.remove('d-none');
        }
    }

    // Establecer las fechas predeterminadas
    window.onload = function() {
        const today = new Date();
        const threeDaysAgo = new Date();

        // Restar 3 días a la fecha actual
        threeDaysAgo.setDate(today.getDate() - 3);

        // Formatear las fechas en formato 'YYYY-MM-DD' para el input de tipo date
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Mes en 2 dígitos
            const day = String(date.getDate()).padStart(2, '0'); // Día en 2 dígitos
            return `${year}-${month}-${day}`;
        };

        // Establecer las fechas en los campos correspondientes
        document.getElementById('startDate').value = formatDate(threeDaysAgo);  // Fecha 3 días antes
        document.getElementById('endDate').value = formatDate(today);           // Fecha actual
    };

    // Validar fechas y manejar eventos de botones
    function generateReport(format) {
        const reportType = document.getElementById('reportType').value;
        const errorContainer = document.getElementById('errorContainer');

        // Validar que se haya seleccionado un tipo de reporte
        if (reportType === '1') {
            errorContainer.textContent = '⚠️ Por favor, selecciona un tipo de reporte válido antes de continuar.';
            errorContainer.classList.remove('d-none');
            return;
        }

        // Validar las fechas
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        if (!startDate || !endDate) {
            errorContainer.textContent = '⚠️ Por favor, selecciona ambas fechas antes de continuar.';
            errorContainer.classList.remove('d-none');
            return;
        }

        // Ocultar el mensaje de error
        errorContainer.classList.add('d-none');

        // Actualizar el contenido del reporte (para consulta)
        const reportTitle = document.getElementById('reportTitle');
        const resultContent = document.getElementById('resultContent');
        reportTitle.textContent = `Reporte de ${reportType}`;
        resultContent.innerHTML = `
            <p><strong>Formato:</strong> ${format.toUpperCase()}</p>
            <p><strong>Tipo de reporte:</strong> ${reportType}</p>
            <p><strong>Desde:</strong> ${startDate}</p>
            <p><strong>Hasta:</strong> ${endDate}</p>
        `;
    }
</script>
@endsection
