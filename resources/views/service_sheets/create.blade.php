@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1 class="mb-4">Generar Hoja de Servicio</h1>

        <!-- Sección de opciones -->
        <div class="card mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5 class="card-title m-0">Opciones</h5>
                <h4 class="card-title m-0">Numero de ticket: {{ $data->ticket->id }}</h4>
                <button id="add-sheet" class="btn btn-primary">Añadir otra hoja</button>
            </div>
        </div>

        <!-- Contenedor de hojas de servicio -->
        <div id="sheets-container">
            <div class="card sheet-entry mb-3" data-sheet-id="1">
                <div class="card-body">
                    <!-- Encabezado con el título y botón de eliminación -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Hoja de Servicio #1</h5>

                    </div>

                    <!-- Información General -->
                    <div class="row">
                        @php
                            $departamento = $data->ticket->user->departament->name ?? 'No especificado';
                            $usuario = $data->ticket->user->name;
                            $tecnico = $data->technician->user->name;
                        @endphp

                        @foreach ([['label' => '📅 Fecha', 'name' => 'date[]', 'value' => '', 'class' => 'date-value', 'hidden' => true], ['label' => '🏢 Unidad/Depto.', 'name' => 'department[]', 'value' => $departamento], ['label' => '👤 Usuario', 'name' => 'user_id[]', 'value' => $usuario], ['label' => '🛠 Técnico', 'name' => 'technician_id[]', 'value' => $tecnico]] as $field)
                            <div class="col-md-3 col-12 mb-3">
                                <div class="info-box">
                                    <span class="info-label">{{ $field['label'] }}</span>
                                    <span class="info-value {{ $field['class'] ?? '' }}">{{ $field['value'] }}</span>
                                    <input type="hidden" name="{{ $field['name'] }}" value="{{ $field['value'] }}">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Selección de Categoría y Equipos -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label for="category-hardware" class="form-label">Categoría de Equipo</label>
                            <select id="category-hardware" class="form-control category-hardware">
                                <option value="">Seleccione una categoría</option>
                                @foreach ($CategoryListhardware as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="hardware-list" class="form-label">Equipo</label>
                            <select id="hardware-list" class="form-control hardware-list">
                                <option value="">Seleccione un equipo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Datos del equipo seleccionado -->
                    <div id="hardware-details" class="container mt-3" style="display: none;">
                        <div class="row">
                            @foreach ([['label' => '🏷 Código de Inventario', 'id' => 'inventory_code'], ['label' => '🔢 Número de Serie', 'id' => 'serial_number'], ['label' => '📦 Modelo', 'id' => 'model_name'], ['label' => '⏳ Garantía', 'id' => 'warranty_expiration_date'], ['label' => '📌 Estado', 'id' => 'status']] as $field)
                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <div class="info-box p-3 border rounded shadow-sm">
                                        <span class="info-label font-weight-bold">{{ $field['label'] }}</span>
                                        <span class="info-value d-block mt-1" id="{{ $field['id'] }}"></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Código de Barras -->

                    </div>


                    <!-- Selección de Categoría e Insumos -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="category-supplies" class="form-label">Categoría de Insumo</label>
                            <select id="category-supplies" class="form-control category-supplies">
                                <option value="">Seleccione una categoría</option>
                                @foreach ($CategoryListInsumo as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="supply-list" class="form-label">Insumo</label>
                            <select id="supply-list" class="form-control supply-list">
                                <option value="">Seleccione un insumo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Botón para agregar insumo -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <button class="btn btn-success add-supply">Agregar Insumo</button>
                        </div>
                    </div>

                    <!-- Tabla de insumos de la hoja -->
                    <table class="table mt-3 supply-table">
                        <thead>
                            <tr>
                                <th>Insumo</th>
                                <th>Cantidad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="supply-list-table">
                            <!-- Insumos seleccionados se mostrarán aquí -->
                        </tbody>
                    </table>

                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Observaciones</label>
                        <textarea name="observations" class="form-control" rows="3"></textarea>
                    </div>

                    <div id="hardware-details2" class="container mt-3" style="display: none;">
                        <div class="row">
                            <div class="col-12 text-center mt-3">
                                <div class="info-box p-3 border rounded shadow-sm">
                                    <span class="info-label font-weight-bold">🖨 Código de Barras</span>
                                    <img id="barcode_image" src="" alt="Código de barras" class="img-fluid mt-2">
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>

        <style>
            /* Estilos para mejorar la presentación */
            .info-box {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 12px;
                text-align: center;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .info-label {
                font-weight: bold;
                color: #6c757d;
                display: block;
                font-size: 0.9rem;
            }

            .info-value {
                font-size: 1.2rem;
                font-weight: bold;
                color: #333;
            }

            @media (max-width: 768px) {
                .info-box {
                    padding: 10px;
                }

                .info-value {
                    font-size: 1rem;
                }
            }
        </style>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function updateCurrentDateTime() {
                let now = new Date();
                let date = now.toISOString().split('T')[0]; // YYYY-MM-DD
                let time = now.toLocaleTimeString('es-ES', {
                    hour12: false
                });

                let formattedDateTime = `${date} ${time}`;

                document.querySelectorAll(".date-value").forEach(el => el.innerText = formattedDateTime);
                document.querySelectorAll(".hidden-date").forEach(el => el.value = formattedDateTime);
            }

            updateCurrentDateTime();
            setInterval(updateCurrentDateTime, 1000);

            function initializeSelect2(selector) {
                $(selector).select2({
                    placeholder: "Seleccione una opción",
                    theme: "bootstrap-5",
                    width: '100%',
                });
            }

            function initializeServiceSheet(selectors) {
                selectors.forEach(selector => initializeSelect2(selector));
            }

            initializeServiceSheet(['.hardware-list', '.category-hardware', '.category-supplies', '.supply-list']);

            let sheetCount = 2;
            document.getElementById("add-sheet").addEventListener("click", function() {
                let newSheet = document.createElement("div");
                newSheet.classList.add("card", "sheet-entry", "mb-3"); // Agregado "service-sheet"
                newSheet.setAttribute("data-sheet-id", sheetCount);
                newSheet.innerHTML = `
                    <div class="card-body">
                        <!-- Encabezado con el título y botón de eliminación -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Hoja de Servicio #${sheetCount}</h5>
                            <button class="btn btn-danger btn-sm delete-sheet"><i class="fa fa-trash "></i></button>
                        </div>

                        <!-- Información General -->
                        <div class="row">

                            @foreach ([['label' => '📅 Fecha', 'name' => 'date[]', 'value' => '', 'class' => 'date-value', 'hidden' => true], ['label' => '🏢 Unidad/Depto.', 'name' => 'department[]', 'value' => $departamento], ['label' => '👤 Usuario', 'name' => 'user_id[]', 'value' => $usuario], ['label' => '🛠 Técnico', 'name' => 'technician_id[]', 'value' => $tecnico]] as $field)
                                <div class="col-md-3 col-12 mb-3">
                                    <div class="info-box">
                                        <span class="info-label">{{ $field['label'] }}</span>
                                        <span class="info-value {{ $field['class'] ?? '' }}">{{ $field['value'] }}</span>
                                        <input type="hidden" name="{{ $field['name'] }}" value="{{ $field['value'] }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Selección de Categoría y Equipos -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <label for="category-hardware" class="form-label">Categoría de Equipo</label>
                                <select id="category-hardware" class="form-control category-hardware">
                                    <option value="">Seleccione una categoría</option>
                                    @foreach ($CategoryListhardware as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="hardware-list" class="form-label">Equipo</label>
                                <select id="hardware-list" class="form-control hardware-list">
                                    <option value="">Seleccione un equipo</option>
                                </select>
                            </div>
                        </div>

                        <!-- Datos del equipo seleccionado -->
                        <div id="hardware-details" class="container mt-3" style="display: none;">
                            <div class="row">
                                @foreach ([['label' => '🏷 Código de Inventario', 'id' => 'inventory_code'], ['label' => '🔢 Número de Serie', 'id' => 'serial_number'], ['label' => '📦 Modelo', 'id' => 'model_name'], ['label' => '⏳ Garantía', 'id' => 'warranty_expiration_date'], ['label' => '📌 Estado', 'id' => 'status']] as $field)
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <div class="info-box p-3 border rounded shadow-sm">
                                            <span class="info-label font-weight-bold">{{ $field['label'] }}</span>
                                            <span class="info-value d-block mt-1" id="{{ $field['id'] }}"></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Código de Barras -->
                            <div class="row">
                                <div class="col-12 text-center mt-3">
                                    <div class="info-box p-3 border rounded shadow-sm">
                                        <span class="info-label font-weight-bold">🖨 Código de Barras</span>
                                        <img id="barcode_image" src="" alt="Código de barras" class="img-fluid mt-2">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Selección de Categoría e Insumos -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="category-supplies" class="form-label">Categoría de Insumo</label>
                                <select id="category-supplies" class="form-control category-supplies">
                                    <option value="">Seleccione una categoría</option>
                                    @foreach ($CategoryListInsumo as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="supply-list" class="form-label">Insumo</label>
                                <select id="supply-list" class="form-control supply-list">
                                    <option value="">Seleccione un insumo</option>
                                </select>
                            </div>
                        </div>
                        <!-- Botón para agregar insumo -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <button class="btn btn-success add-supply">Agregar Insumo</button>
                            </div>
                        </div>

                        <!-- Tabla de insumos de la hoja -->
                        <table class="table mt-3 supply-table">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th>Cantidad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="supply-list-table">
                                <!-- Insumos seleccionados se mostrarán aquí -->
                            </tbody>
                        </table>
                    </div>
        `;
                document.getElementById("sheets-container").appendChild(newSheet);
                updateCurrentDateTime();
                initializeServiceSheet(['.hardware-list', '.category-hardware', '.category-supplies',
                    '.supply-list',
                ]);
            });

            $(document).on("click", ".delete-sheet", function() {
                let sheet = $(this).closest(".service-sheet");
                Swal.fire({
                    title: "¿Eliminar esta hoja?",
                    text: "No podrás deshacer esta acción",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        sheet.remove();
                    }
                });
            });

            $(document).on("change", ".category-hardware", function() {
                let categoryId = $(this).val();
                let hardwareList = $(this).closest(".sheet-entry").find(
                    ".hardware-list"); // Cambiado .service-sheet a .sheet-entry

                if (categoryId) {
                    $.get(`/service-sheets/get-hardware/${categoryId}`, function(data) {


                        hardwareList.empty().append(
                            '<option value="">Seleccione un equipo</option>');

                        if (data.length > 0) {
                            data.forEach(item => {
                                hardwareList.append(
                                    `<option value="${item.id}">${item.inventory_code}</option>`
                                );
                            });
                        } else {
                            hardwareList.append(
                                '<option value="">No hay equipos disponibles</option>');
                        }

                        hardwareList.trigger(
                            "change.select2"); // Forzar actualización de Select2 si se usa
                    }).fail(() => alert("Error al cargar los equipos."));
                } else {
                    hardwareList.empty().append('<option value="">Seleccione un equipo</option>');
                }
            });

            let selectedSupplies = {}; // Objeto para almacenar insumos por hoja

            // Cambiar de categoría para cargar insumos
            $(document).on("change", ".category-supplies", function() {
                let sheetEntry = $(this).closest(".sheet-entry");
                let supplyList = sheetEntry.find(".supply-list");
                let categoryId = $(this).val();

                if (categoryId) {
                    $.get(`/service-sheets/get-supplies/${categoryId}`, function(data) {
                        supplyList.empty().append('<option value="">Seleccione un insumo</option>');

                        if (data.length > 0) {
                            data.forEach(item => {
                                supplyList.append(
                                    `<option value="${item.id}">${item.name}</option>`);
                            });
                        } else {
                            supplyList.append(
                                '<option value="">No hay insumos disponibles</option>');
                        }
                    }).fail(() => {
                        Swal.fire("Error", "Error al cargar los insumos.", "error");
                    });
                } else {
                    supplyList.empty().append('<option value="">Seleccione un insumo</option>');
                }
            });

            // Agregar insumo a la lista de la hoja
            $(document).on("click", ".add-supply", function() {
                let sheetEntry = $(this).closest(".sheet-entry");
                let sheetId = sheetEntry.data("sheet-id"); // ID único por hoja
                let supplyId = sheetEntry.find(".supply-list").val();
                let supplyName = sheetEntry.find(".supply-list option:selected").text();

                if (!supplyId) {
                    Swal.fire("Atención", "Seleccione un insumo.", "warning");
                    return;
                }

                // Asegurar que la hoja tenga su propio array de insumos
                if (!selectedSupplies[sheetId]) {
                    selectedSupplies[sheetId] = [];
                }

                // Verificar si el insumo ya está en esta hoja
                let existingSupply = selectedSupplies[sheetId].find(s => s.id == supplyId);
                if (existingSupply) {
                    Swal.fire("Atención", "Este insumo ya fue agregado en esta hoja.", "warning");
                    return;
                }

                // Agregar insumo a la hoja específica
                selectedSupplies[sheetId].push({
                    id: supplyId,
                    name: supplyName,
                    quantity: 1
                });

                // Actualizar tabla de esta hoja
                updateSupplyTable(sheetEntry, sheetId);
            });

            // Función para actualizar la tabla de insumos de la hoja específica
            function updateSupplyTable(sheetEntry, sheetId) {
                let tableBody = sheetEntry.find(".supply-list-table");
                tableBody.empty();

                selectedSupplies[sheetId].forEach((supply, index) => {
                    tableBody.append(`
            <tr>
                <td>${supply.name}</td>
                <td><input type="number" class="form-control supply-quantity" data-sheet-id="${sheetId}" data-index="${index}" value="${supply.quantity}" min="1"></td>
                <td><button class="btn btn-danger btn-sm remove-supply" data-sheet-id="${sheetId}" data-index="${index}">Eliminar</button></td>
            </tr>
        `);
                });
            }

            // Actualizar cantidad del insumo en la hoja específica
            $(document).on("input", ".supply-quantity", function() {
                let sheetId = $(this).data("sheet-id");
                let index = $(this).data("index");
                selectedSupplies[sheetId][index].quantity = $(this).val();
            });

            // Eliminar insumo de la hoja específica con SweetAlert de confirmación
            $(document).on("click", ".remove-supply", function() {
                let sheetId = $(this).data("sheet-id");
                let index = $(this).data("index");

                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "Este insumo será eliminado de la hoja.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        selectedSupplies[sheetId].splice(index, 1); // Eliminar del array

                        let sheetEntry = $(`.sheet-entry[data-sheet-id="${sheetId}"]`);
                        updateSupplyTable(sheetEntry, sheetId);

                        Swal.fire("Eliminado", "El insumo ha sido eliminado.", "success");
                    }
                });
            });






            $(document).on("change", ".hardware-list", function() {
                let hardwareId = $(this).val();
                let serviceSheet = $(this).closest(".sheet-entry"); // Asegura que es el contenedor correcto
                let detailsContainer = serviceSheet.find("#hardware-details"); // Busca el ID correcto
                let detailsContainer2 = serviceSheet.find("#hardware-details2"); // Busca el ID correcto
                if (hardwareId) {
                    $.get(`/service-sheets/get-hardware-details/${hardwareId}`, function(data) {
                        console.log("Respuesta del servidor:", data);

                        if (data.error) {
                            alert(data.error);
                            detailsContainer.hide();
                            detailsContainer2.hide();
                            return;
                        }

                        console.log("Buscando detalles en:", detailsContainer);

                        // Actualizar los valores dentro de hardware-details
                        detailsContainer.find("#model_name").text(data.model_name || "N/A");
                        detailsContainer.find("#inventory_code").text(data.inventory_code || "N/A");
                        detailsContainer.find("#serial_number").text(data.serial_number || "N/A");
                        detailsContainer.find("#warranty_expiration_date").text(data
                            .warranty_expiration_date || "N/A");
                        detailsContainer.find("#status").text(data.status || "N/A");

                        let barcodeImg = detailsContainer2.find("#barcode_image");
                        if (data.barcode_path) {
                            barcodeImg.attr("src", data.barcode_path).show();
                        } else {
                            barcodeImg.hide();
                        }

                        detailsContainer.css("display", "block").fadeIn(); // Mostrar detalles
                        detailsContainer2.css("display", "block").fadeIn(); // Mostrar detalles
                    }).fail(() => {
                        alert("Error al obtener los detalles del equipo.");
                        detailsContainer.hide();
                        detailsContainer2.hide();
                    });
                } else {
                    detailsContainer.hide();
                    detailsContainer2.hide();
                }
            });
        });
    </script>
@endsection
