@extends('layouts.user_type.auth')

@section('content')
<div class="container" style="min-height: 75vh;">
    <h2 class="my-4 card p-3">{{ $ticket->technician_id ? 'Reasignar Ticket' : 'Asignar Ticket' }}</h2>

    <fieldset class="border rounded-3 p-3 mb-3 card ">
        <legend class="w-auto">Información del Ticket</legend>
        <div class="row g-3">
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="title" class="form-label">Título del Ticket</label>
                <p>{{ $ticket->title }}</p>
            </div>
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="description" class="form-label">Descripción del problema</label>
                <p>{{ $ticket->description }}</p>
            </div>
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="user_id" class="form-label">Usuario</label>
                <p>{{ $ticket->user->name }}</p>
            </div>
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="user_id" class="form-label">Departamento</label>
                <p>{{ $ticket->user->departament->name }}</p>
            </div>
        </div>
    </fieldset>

    @if($ticket->technician && $ticket->assignments->isNotEmpty())
    <fieldset class="border rounded-3 p-3 mb-3 card bg-light">
        <legend class="w-auto">Información del Técnico Asignado Anteriormente</legend>
        <div class="row g-3">
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="technician_name" class="form-label">Técnico Asignado</label>
                <p>{{ $ticket->technician->user->name }}</p>
            </div>
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="assigned_at" class="form-label">Fecha de Asignación</label>
                <p>{{ $ticket->assignments->last()->initial_date ? $ticket->assignments->last()->initial_date->format('d/m/Y H:i') : 'No disponible' }}</p>
            </div>
        </div>
    </fieldset>
@endif


    <fieldset class="border rounded-3 p-3 mb-3 card ">
        <legend class="w-auto">{{ $ticket->technician_id ? 'Reasignación del Ticket' : 'Asignación del Ticket' }}</legend>

        <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
            @csrf

            <div class="row">
                <!-- Tarea Asignada -->
                <div class="col-md-6 mb-3">
                    <label for="task" class="form-label">Nombre de la Tarea </label>
                    <input type="text" class="form-control" id="task" name="task" required value="{{ $ticket->task ?? '' }}">
                </div>

                <!-- Fecha de llegada -->
                <div class="col-md-6 mb-3">
                    <label for="fecha_de_llegada" class="form-label">Fecha de llegada</label>
                    <input type="datetime-local" class="form-control" id="initial_date" name="initial_date" required value="{{ $ticket->initial_date ?? '' }}">
                </div>

                <!-- Prioridad -->
                <div class="col-md-4 col-sm-12 mb-3">
                    <label for="priority" class="form-label">Prioridad</label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="baja" {{ $ticket->priority == 'baja' ? 'selected' : '' }}>Baja</option>
                        <option value="media" {{ $ticket->priority == 'media' ? 'selected' : '' }}>Media</option>
                        <option value="alta" {{ $ticket->priority == 'alta' ? 'selected' : '' }}>Alta</option>
                    </select>
                </div>

                <!-- Especialidad -->
                <div class="col-md-4 col-sm-12 mb-3">
                    <label for="specialty_id" class="form-label">Especialidad</label>
                    <select class="form-select" id="specialty_id" name="specialty_id" required>
                        <option value="">Seleccione una especialidad</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" {{ $ticket->specialty_id == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Técnico (Este campo será dinámico) -->
                <div class="col-md-4 col-sm-12 mb-3">
                    <label for="technician_id" class="form-label">Técnico</label>
                    <select class="form-select" id="technician_id" name="technician_id" required>
                        <option value="">Seleccione un técnico</option>
                        @if ($ticket->technician)
                            <option value="{{ $ticket->technician->id }}" selected>{{ $ticket->technician->user->name }}</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">{{ $ticket->technician_id ? 'Reasignar Ticket' : 'Asignar Ticket' }}</button>
            </div>
        </form>
    </fieldset>

</div>

<script>
    $(document).ready(function() {
        function initializeSelect2(selector) {
            $(selector).select2({
                placeholder: "Seleccione una opción",
                theme: "bootstrap-5",
                width: '100%',
            });
        }

        initializeSelect2('#specialty_id');
        initializeSelect2('#technician_id');

        // Captura el cambio en el select de especialidad
        $('#specialty_id').change(function() {
            var specialty_id = $(this).val();
            var technicianSelect = $('#technician_id');

            if (specialty_id === '') {
                technicianSelect.html('<option value="">Seleccione un técnico</option>');
                return;
            }

            // Solicitud AJAX para obtener los técnicos por especialidad
            $.ajax({
                url: '/get-technicians/' + specialty_id,
                type: 'GET',
                success: function(data) {
                    technicianSelect.html('<option value="">Seleccione un técnico</option>');

                    if (data.length > 0) {
                        data.forEach(function(technician) {
                            technicianSelect.append('<option value="' + technician.id + '">' + technician.user.name + '</option>');
                        });
                    } else {
                        technicianSelect.append('<option value="">No hay técnicos disponibles</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                }
            });
        });
    });
</script>
@endsection
