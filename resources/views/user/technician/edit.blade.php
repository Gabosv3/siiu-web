@extends('layouts.user_type.auth')

@section('content')
<div class="container-sm card d-flex justify-content-center align-items-center h" style="min-height: 75vh;">
    <fieldset class="border rounded-3 p-5">
        <legend class="float-none w-auto h2">Editar Técnico</legend>
        <form class="align-items-center" id="editTechnicianForm" method="POST" action="{{ route('technician.update', $technician->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="specialty_id" class="form-label">Especialidad</label>
                <div class="d-flex align-items-center">
                    <select id="specialty_id" name="specialty_id" class="form-select" required>
                        <option value="">Seleccionar Especialidad</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" {{ $technician->specialty_id == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-green-600 ms-2 m-auto text-center" title="Agregar Especialidad" data-bs-toggle="modal" data-bs-target="#addSpecialtyModal" style="height: 38px;">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label for="available" class="form-label">Disponible</label>
                <select id="available" name="available" class="form-select" required>
                    <option value="1" {{ $technician->available == 1 ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ $technician->available == 0 ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary text-center">Actualizar Técnico</button>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </fieldset>
</div>

<!-- Modal para agregar nueva especialidad -->
<div class="modal fade" id="addSpecialtyModal" tabindex="-1" aria-labelledby="addSpecialtyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSpecialtyModalLabel">Agregar Nueva Especialidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createSpecialtyForm">
                    @csrf
                    <div class="mb-3">
                        <label for="specialty_name" class="form-label">Nombre de Especialidad</label>
                        <input type="text" class="form-control" id="specialty_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar Especialidad</button>
                </form>
            </div>
        </div>
    </div>
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

        initializeSelect2('#user_id');
        initializeSelect2('#specialty_id');

        // Manejo del formulario para crear una nueva especialidad
        $('#createSpecialtyForm').on('submit', function(e) {
            e.preventDefault();
            let specialtyName = $('#specialty_name').val();

            $.ajax({
                url: "{{ route('specialties.store') }}",
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    name: specialtyName
                },
                success: function(response) {
                    // Mensaje de éxito
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Nueva especialidad creada con éxito.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });

                    // Cierra el modal
                    $('#addSpecialtyModal').modal('hide');
                    $('#specialty_name').val('');

                    // Crea una nueva opción para el select de especialidades
                    let newOption = new Option(response.name, response.id, true, true);
                    $('#specialty_id').append(newOption).trigger('change');
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un error al crear la especialidad.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });
    });
</script>
@endsection
