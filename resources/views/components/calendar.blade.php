@extends('layouts.user_type.auth')

@section('content')
    <div class="card p-5" style="min-height: 75vh;">
        <div style="display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
            <div id="calendar" style="width: 100%; max-width: 900px; height: 100%;"></div>
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
                    // Redirige a la vista de la hoja de servicio
                    window.location.href = `/service-sheet/${info.event.id}`;
                    info.jsEvent.preventDefault();
                },

                // Vista responsiva
                windowResize: function(view) {
                    if (window.innerWidth < 768) {
                        calendar.changeView(
                        'listMonth'); // Cambiar a vista de lista en pantallas pequeñas
                    } else {
                        calendar.changeView('dayGridMonth'); // Vista normal en pantallas grandes
                    }
                },
            });
            calendar.render();
        });
    </script>
@endsection
