@extends('layouts.user_type.auth')

@section('content')

<div style="display: flex; justify-content: center; align-items: center; ">
    <div id="calendar" style="width: 100%; max-width: 900px; height: 500px;"></div>
</div>


<!-- FullCalendar JS desde CDN -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            events: '/api/assignments', // Ruta que carga las asignaciones desde el servidor
            locale: 'es' // Cambiar el idioma a español
        });
        calendar.render();
    });
</script>
@endsection