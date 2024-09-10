$(document).ready(function() {
    // Inicializar Select2
    function initializeSelect2(selector) {
        $(selector).select2({
            placeholder: "Seleccione una opción",
            theme: "bootstrap-5",
            width: '100%',
        });
    }

    // Inicializar los selects
    initializeSelect2('#fabricante_id');
    initializeSelect2('#modelo_id');
    initializeSelect2('.js-select-ubicacion');

    // Manejar cambio en el select de fabricantes
    $('#fabricante_id').on('change', function() {
        const fabricanteId = $(this).val();
        const fabricanteText = $(this).find('option:selected').text();

        $('#addModeloBtn').prop('disabled', !fabricanteId);
        $('#selectedFabricante').text(fabricanteText);
        $('#fabricante_id_modelo').val(fabricanteId);

        if (fabricanteId) {
            // Obtener modelos por fabricante
            $.ajax({
                url: `/modelos/por-fabricante/${fabricanteId}`,
                method: 'GET',
                success: function(response) {
                    const $modeloSelect = $('#modelo_id').empty();
                    response.modelos.forEach(function(modelo) {
                        $modeloSelect.append(new Option(modelo.nombre, modelo.id));
                    });
                },
                error: function() {
                    console.error('Error al obtener modelos.');
                }
            });
        } else {
            $('#selectedFabricante').text('');
            $('#fabricante_id_modelo').val('');
            $('#modelo_id').empty().append('<option value="" disabled selected>Seleccione un modelo</option>');
        }
    });

    // Manejar la creación de fabricantes
    $('#createFabricanteForm').on('submit', function(e) {
        e.preventDefault();
        const nombre = $('#nuevo_fabricante_nombre').val();

        $.ajax({
            url: '{{ route('fabricantes.store') }}',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                nombre: nombre
            },
            success: function(response) {
                $('#createFabricanteModal').modal('hide');
                $('#nuevo_fabricante_nombre').val('');

                // Añadir el nuevo fabricante al select
                const newOption = new Option(response.nombre, response.id, true, true);
                $('#fabricante_id').append(newOption).trigger('change');

                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Nuevo fabricante creado con éxito.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un error al crear el fabricante.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });

    // Manejar la creación de modelos
    $('#createModeloForm').on('submit', function(e) {
        e.preventDefault();
        const nombre = $('#nuevo_modelo_nombre').val();
        const fabricanteId = $('#fabricante_id_modelo').val();

        $.ajax({
            url: '{{ route('modelos.store') }}',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                nombre: nombre,
                fabricante_id: fabricanteId
            },
            success: function(response) {
                $('#createModeloModal').modal('hide');
                $('#nuevo_modelo_nombre').val('');

                // Añadir el nuevo modelo al select de modelos
                const newOption = new Option(response.nombre, response.id, true, true);
                $('#modelo_id').append(newOption).trigger('change');

                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Nuevo modelo creado con éxito.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });
            },
            error: function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un error al crear el modelo.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});

