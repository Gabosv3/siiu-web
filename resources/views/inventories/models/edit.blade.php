@extends('layouts.user_type.auth')

@section('content')
    <div class="card mt-4 p-3" style="min-height: 80vh;">
        <form action="{{ route('models.update', $model->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nombre del Modelo -->
            <div class="form-group">
                <label for="name">Nombre del modelo:</label>
                <input type="text" id="name" name="name" class="form-control"
                    value="{{ old('name', $model->name) }}">
            </div>

            <!-- Características Existentes -->
            <div class="form-group mt-3">
                <label for="characteristics" class="bold h3">Características:</label>
                <div class="d-flex flex-wrap" id="characteristics-list">
                    @foreach ($model->characteristics as $characteristic)
                        <div class="col-md-3 p-2 characteristic-item" id="characteristic_{{ $characteristic->id }}">
                            <label for="characteristic_value_{{ $characteristic->id }}">{{ $characteristic->name }}</label>
                            <div class="d-flex align-items-center">
                                <input type="text" id="characteristic_value_{{ $characteristic->id }}"
                                    name="characteristics_values[{{ $characteristic->id }}]" class="form-control"
                                    value="{{ old('characteristics_values.' . $characteristic->id, $characteristic->pivot->value) }}"
                                    placeholder="Valor de la característica">

                                <button type="button" class="btn btn-outline-danger btn-sm delete-characteristic m-2"
                                    data-url="{{ route('models.removeCharacteristic', [$model->id, $characteristic->id]) }}"
                                    title="Eliminar característica">
                                    🗑
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Botón para abrir el modal -->
            <div class="form-group">
                <button type="button" class="btn btn-secondary" data-toggle="modal"
                    data-target="#selectCharacteristicModal">
                    Agregar Nueva Característica
                </button>
            </div>

            <!-- Botón de Guardar -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>

    <!-- Modal para seleccionar características -->
    <div class="modal fade" id="selectCharacteristicModal" tabindex="-1" role="dialog"
        aria-labelledby="selectCharacteristicModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectCharacteristicModalLabel">Seleccionar Característica</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="selectCharacteristicForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="existing_characteristic">Característica existente:</label>
                            <select id="existing_characteristic" class="form-control"
                                data-placeholder="Seleccione una característica">
                                <option value="" disabled selected></option>
                                @foreach ($characteristics as $characteristic)
                                    <option value="{{ $characteristic->id }}"
                                        title="Descripción: {{ $characteristic->description }}">
                                        {{ $characteristic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="characteristic_value">Valor de la característica:</label>
                            <input type="text" id="characteristic_value" name="characteristic_value" class="form-control"
                                placeholder="Ingresa el valor">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="addExistingCharacteristic">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function initializeSelect2(selector) {
                $(selector).select2({
                    dropdownParent: $('#selectCharacteristicModal'),
                    placeholder: "Seleccione una característica",
                    theme: "bootstrap-5",
                    width: '100%'
                });
            }

            $('#selectCharacteristicModal').on('shown.bs.modal', function() {
                initializeSelect2('#existing_characteristic');
            });

            // Agregar nueva característica al formulario principal
            $('#addExistingCharacteristic').on('click', function() {
                var characteristicId = $('#existing_characteristic').val();
                var characteristicValue = $('#characteristic_value').val();

                if (characteristicId && characteristicValue) {
                    $.ajax({
                        url: '{{ route('models.addCharacteristic', $model->id) }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            characteristic_id: characteristicId,
                            value: characteristicValue
                        },
                        success: function(response) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: response.message,
                                icon: 'success',
                            }).then(() => {
                                var newField = `
                                    <div class="col-md-3 p-2 characteristic-item" id="characteristic_${response.id}">
                                        <label for="characteristic_value_${response.id}">${response.name}</label>
                                        <div class="d-flex align-items-center">
                                            <input type="text" id="characteristic_value_${response.id}" name="characteristics_values[${response.id}]" class="form-control" value="${response.value}" placeholder="Valor de la característica">
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-characteristic m-2" data-url="{{ url('models/${response.id}/remove-characteristic') }}">🗑</button>
                                        </div>
                                    </div>
                                `;
                                $('#characteristics-list').append(newField);
                                $('#selectCharacteristicModal').modal('hide');
                                $('#existing_characteristic').val(null).trigger(
                                    'change');
                                $('#characteristic_value').val('');
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                title: 'Error',
                                text: response.responseJSON.message,
                                icon: 'error',
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor selecciona una característica y proporciona un valor.',
                        icon: 'error',
                    });
                }
            });

            // Eliminar característica
            $(document).on('click', '.delete-characteristic', function() {
                const button = $(this);
                const url = button.data('url');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Confirmación final',
                            text: "¿Realmente quieres eliminar esta característica?",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((secondResult) => {
                            if (secondResult.isConfirmed) {
                                $.ajax({
                                    url: url,
                                    method: 'POST',
                                    data: {
                                        _method: 'DELETE',
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            title: '¡Eliminado!',
                                            text: response.message,
                                            icon: 'success',
                                        }).then(() => {
                                            button.closest(
                                                '.characteristic-item'
                                            ).remove();
                                        });
                                    },
                                    error: function() {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'No se pudo eliminar la característica.',
                                            icon: 'error',
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Validación',
                    html: `
                    <ul style="text-align: left;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif
@endsection
