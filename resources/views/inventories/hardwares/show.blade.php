@extends('layouts.user_type.auth')

@section('content')


<!-- Tabs Navigation -->
<div class=" p-3" style="min-height: 80vh;">
    <div class="card p-3" style="width: 100%;">
        <div class="row g-0 mb-3 align-items-center justify-content-evenly">
            <!-- Imagen de la tarjeta -->
            <div class="col-md-2 d-flex align-items-center justify-content-center">
                <img src="{{ $hardware->category->image }}" class="img-fluid rounded-start" alt="Imagen de la categoría" style="width: 15vw;">
            </div>
            <!-- Contenido de la tarjeta -->
            <div class="col-md-3">
                <div class="card-body d-flex flex-column ">
                    <h3 class="card-title text-uppercase">{{ $hardware->name }}</h3>
                    <p class="text-muted ">{{ $hardware->inventory_code }}</p>
                    <small class="text-muted">Actualizado {{ $hardware->updated_at->format('M d Y, H:i') }}</small>
                </div>
            </div>
            <div class="col-md-2 card" style="background-color: #ECECEC;">
                <div class="d-flex align-items-center p-3" style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="d-flex align-items-center me-auto">
                        <i class="fa fa-user fa-2x" style="color: #6c757d; margin-right: 10px;"></i>
                        <div>
                            <p class="m-0 text-uppercase" style="color: #6c757d; font-weight: 500;">Asignado a</p>
                            <p class="m-0 text-uppercase" style="color: #6c757d; " id="assignationText">
                                {{ $hardware->hardwareAssigned->user->name ?? 'No asignado' }}
                            </p>
                        </div>
                    </div>

                    <button type="button" class="btn btn-green-600 d-flex align-items-center justify-content-center my-auto" title="Editar " style=" height: 100%; box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="fa fa-edit"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-2 card" style="background-color: #ECECEC;">
                <div class="d-flex align-items-center p-3" style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="d-flex align-items-center justify-content-evenly w-100  me-auto">
                        <i class="fa fa-map fa-2x " style="color: #6c757d; margin-right: 10px;"></i>
                        <div>
                            <p class="m-0 text-uppercase" style="color: #6c757d; font-weight: 500;">Ubicación en</p>
                            <p class="m-0" style="color: #6c757d;" id="departamentText"> {{ $hardware->hardwareAssigned->user->departament->name ?? 'No Ubicado'}} </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 card" style="background-color: #ECECEC;">
                <div class="d-flex align-items-center  p-3" style="background-color: #f8f9fa; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="d-flex align-items-center justify-content-evenly w-100 me-auto">
                        <i class="fa fa-user fa-2x" style="color: #6c757d; margin-right: 10px;"></i>
                        <div>
                            <p class="m-0 text-uppercase" style="color: #6c757d; font-weight: 500;">Estado</p>
                            <p class="m-0 text-capitalize" style="color: #6c757d;" id="statusText">{{ $hardware->status }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-around align-items-center t">
            <ul class="nav nav-tabs " id="myTab" role="tablist">
                <li class="nav-item ms-3" role="presentation">
                    <button class="nav-link active " id="company-tab" data-bs-toggle="tab" data-bs-target="#company-tab-pane" type="button" role="tab" aria-controls="company-tab-pane" aria-selected="true">EQUIPO</button>
                </li>
                <li class="nav-item ms-3" role="presentation">
                    <button class="nav-link" id="values-tab" data-bs-toggle="tab" data-bs-target="#values-tab-pane" type="button" role="tab" aria-controls="values-tab-pane" aria-selected="false">lICENCIAS</button>
                </li>
                <li class="nav-item ms-3" role="presentation">
                    <button class="nav-link" id="culture-tab" data-bs-toggle="tab" data-bs-target="#culture-tab-pane" type="button" role="tab" aria-controls="culture-tab-pane" aria-selected="false">DOCUMENTOS</button>
                </li>
                <li class="nav-item ms-3" role="presentation">
                    <button class="nav-link" id="philosophy-tab" data-bs-toggle="tab" data-bs-target="#philosophy-tab-pane" type="button" role="tab" aria-controls="philosophy-tab-pane" aria-selected="false">HISTORIAL</button>
                </li>
            </ul>

            <div class="d-flex flex-column align-items-center justify-content-center ms-3">
                <h5 class="mb-3">Código de Barras</h5>
                <div class="mb-3 text-center" id="barcodeDiv">
                    <img src="{{ asset('storage/' . $hardware->barcode_path) }}" class="img-fluid" alt="Código de Barras">
                    <p class="m-0 text-center">{{ $hardware->inventory_code }}</p>
                </div>
                <button onclick="printDiv('barcodeDiv')" class="btn btn-primary"><i class="fa fa-print"></i> Imprimir</button>
            </div>

        </div>


    </div>


    <div class="tab-content  p-3" id="myTabContent">
        <!-- Company Tab Content -->
        <div class="tab-pane fade show active" id="company-tab-pane" role="tabpanel" aria-labelledby="company-tab" tabindex="0">
            <div class="row ">
                <div class="col-6">
                    <div class="card p-3 border" style="background-color: white; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                        <h2>Detalles</h2>
                        <div class="row row-cols-2 row-cols-lg-4 g-2 g-lg-3">
                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Marca</h5>
                                    <p>{{ $hardware->manufacturer->name }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Modelo</h5>
                                    <p>{{ $hardware->model->name }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Procesador</h5>
                                    <p>{{ $hardware->model->name }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Motherboard</h5>
                                    <p>{{ $hardware->model->name }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Ram</h5>
                                    <p>{{ $hardware->model->name }}</p>
                                </div>
                            </div>

                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Graficos</h5>
                                    <p>{{ $hardware->model->name }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Audio</h5>
                                    <p>{{ $hardware->model->name }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Almacenamiento</h5>
                                    <p>{{ $hardware->model->name }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Numero de serie</h5>
                                    <p>{{ $hardware->serial_number }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card p-3 border" style="background-color: white; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                        <h2>Garantia</h2>
                        @if ($hardware->warranty_expiration_date > date('Y-m-d'))
                        <div class="row row-cols-1 row-cols-lg-1 g-2 g-lg-3">
                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Garantia</h5>
                                    <p>{{ $hardware->warranty_expiration_date }}</p>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-3 ">
                                    <h5>Modelo</h5>
                                    <p>{{ $hardware->model->name }}</p>
                                </div>
                            </div>
                        </div>
                        @else
                        <h2>No tiene garantia</h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Values Tab Content -->
        <div class="tab-pane fade" id="values-tab-pane" role="tabpanel" aria-labelledby="values-tab" tabindex="0">
            <h1>Our Values</h1>
            <p>Here we would discuss our core values and what drives our organization.</p>
        </div>

        <!-- Culture Tab Content -->
        <div class="tab-pane fade" id="culture-tab-pane" role="tabpanel" aria-labelledby="culture-tab" tabindex="0">
            <h1>Our Culture</h1>
            <p>We value a work environment that fosters collaboration and innovation.</p>
        </div>

        <!-- Philosophy Tab Content -->
        <div class="tab-pane fade" id="philosophy-tab-pane" role="tabpanel" aria-labelledby="philosophy-tab" tabindex="0">
            <h1>Historial</h1>
            <div class="">

                <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3">
                @foreach ($histories as $history)
                    <div class=" col"  >
                        <div class="p-3 border bg-white ">{{ $history->action }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>



</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Asignar Equipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignmentForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="manager" class="form-label">ENCARGADO:</label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="" data-departament="">Sin encargado</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}" data-departament="{{ $user->departament->name }}">
                                {{ $user->name }}, {{ $user->email }}
                            </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Por favor, seleccione un encargado.
                        </div>
                    </div>

                    <!-- Campo para mostrar el departamento -->
                    <div class="mb-3">
                        <label class="form-label">DEPARTAMENTO:</label>
                        <input type="text" name="departament" id="departament" class="form-control" readonly>
                    </div>

                    <input type="hidden" name="hardware_id" id="hardware_id" value="{{ $hardware->id }}">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="saveChangesButton">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        function initializeSelect2(selector) {
            $(selector).select2({
                dropdownParent: $('#exampleModal'),
                placeholder: "Seleccione una opción",
                theme: "bootstrap-5",
                width: '100%',
            });
        }

        $('#exampleModal').on('shown.bs.modal', function() {
            initializeSelect2('#user_id');
        });

        // Escucha el cambio en el select para actualizar el departamento
        $('#user_id').on('change', function() {
            // Obtén el departamento del usuario seleccionado
            var departament = $(this).find(':selected').data('departament');
            // Muestra el departamento en el campo de texto
            $('#departament').val(departament || "Sin departamento asignado");
        });

        // AJAX para guardar los cambios
        $('#assignmentForm').on('submit', function(e) {
            e.preventDefault();
            const userId = $('#user_id').val();
            const equipmentId = $('#hardware_id').val();

            // Llamada AJAX
            $.ajax({
                url: '{{ route("assign.equipment") }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    user_id: userId,
                    hardware_id: equipmentId,
                },
                success: function(response) {
                    // Mensaje de éxito
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Equipo asignado con éxito.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    });

                    // Cierra el modal
                    $('#exampleModal').modal('hide');

                    // Limpia el campo de entrada
                    $('#user_id').val('').trigger('change');

                    // Actualiza los datos 
                    // Actualizar la visualización de los datos sin recargar la página
                    $('#assignationText').text(response.user_name);
                    $('#departamentText').text(response.departament_name);
                    $('#statusText').text(response.status);

                },
                error: function(xhr) {
                    console.log(xhr); // Ver el objeto completo de la respuesta

                    let errorMessage = 'Error al asignar el equipo.';

                    // Si hay errores de validación
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = '';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errors += value + "\n"; // Concatenar los mensajes de error
                        });
                        errorMessage = errors; // Cambiar el mensaje de error a los errores específicos
                    }

                    Swal.fire({
                        title: 'Error',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });

    });

    function printDiv(divId) {
    var contenido = document.getElementById(divId).innerHTML;
    var ventana = window.open('', '_blank');
    ventana.document.write('<html><head><title>Imprimir Código de Barras</title>');
    ventana.document.write(`
        <style>
            /* Configuración para impresión de ticket de 50x30mm */
            @page {
                size: 50mm 30mm;
                margin: 0;
            }
            body {
                width: 50mm;
                height: 30mm;
                margin: 0;
                font-family: Arial, sans-serif;
            }
            .print-content {
                text-align: center;
                padding: 5px;
            }
            img {
                max-width: 100%;
                height: auto;
            }
            h5, p {
                margin: 0;
                font-size: 10px;
            }
            .sistemas-info {
                font-size: 8px;
                margin-top: 5px;
            }
        </style>
    `);
    
    ventana.document.write('<div class="print-content">' + contenido + '</div>');
    ventana.document.write('<div class="sistemas-info">Sistemas Informáticos</div>');
   
    ventana.document.close();
    ventana.print();
}

</script>





@endsection