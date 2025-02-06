@extends('layouts.user_type.auth')

@section('content')

<div class="card mt-4 p-4 shadow-lg">
    <!-- Título de la sección -->
    <h2 class="mb-4 text-center text-gradient text-primary">Reportes</h2>

    <!-- Sección de Bienvenida -->
    <div class="welcome-section text-center p-4 bg-light rounded">
        <!-- Icono de bienvenida -->
        <div class="icon-wrapper mb-3">
            <i class="fas fa-chart-line fa-4x text-primary"></i>
        </div>

        <!-- Título de bienvenida -->
        <h3 class="text-gradient text-primary mb-3">¡Bienvenido al Módulo de Reportes!</h3>

        <!-- Descripción -->
        <p class="text-muted lead mb-4">
            En esta sección podrás generar y visualizar reportes relacionados con el inventario de equipos, software, licencias, insumos y tickets de soporte técnico.
        </p>

        <!-- Mensaje adicional -->
        <p class="text-muted">
            Utiliza las opciones disponibles para filtrar, buscar y exportar la información que necesites. Si tienes alguna duda, consulta la documentación o contacta al equipo de soporte.
        </p>

       
    </div>

    <!-- Espacio para futuros elementos (filtros, gráficos, tablas, etc.) -->
    <div class="report-options mt-5">
        <!-- Aquí irán los filtros, gráficos, tablas, etc. -->
    </div>
</div>

@endsection
