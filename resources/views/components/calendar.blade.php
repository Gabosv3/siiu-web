@extends('layouts.user_type.auth')

@section('content')
<div class="card p-5" style="min-height: 75vh;">
<div style="display: flex; justify-content: center; align-items: center; ">
    <div id="calendar" style="width: 100%; max-width: 900px; height: 500px;"></div>
</div>
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
            locale: 'es', // Cambiar el idioma a español
            eventClick: function(info) {
                // Redirigir al usuario a la URL del evento al hacer clic
                window.location.href = info.event.url;
                // Evita el comportamiento predeterminado si es necesario
                info.jsEvent.preventDefault();
            },
            eventDataTransform: function(eventData) {
                // Si tu evento no tiene una propiedad `url`, puedes asignarla aquí.
                // Suponiendo que el servidor devuelve una propiedad `id` que se usa para construir la URL
                eventData.url = `/tickets/${eventData.id}`; // Modifica según tu ruta de detalles
                return eventData;
            }
        });
        calendar.render();
    });
</script>

@endsection