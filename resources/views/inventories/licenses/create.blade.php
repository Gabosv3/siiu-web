@extends('layouts.user_type.auth')

@section('content')
<div class="container-md ">
    <h1>Crear Nuevas Licencias</h1>

    <form class="was-validated" action="{{ route('licenses.store') }}" method="POST" id="licenses-form" enctype="multipart/form-data">
        @csrf

        <fieldset class="border rounded-3 p-3 mb-3 card">
            <legend class="float-none w-auto px-3">Importar:</legend>
            <h4>Importar Claves de Licencia desde Archivo</h4>
            <div class="form-group">
                <label for="file">Subir archivo (CSV):</label>
                <input type="file" id="file" class="form-control" accept=".csv">
            </div>

            <div class="form-group">
                <button type="button" id="process-file" class="btn btn-primary">Procesar Archivo</button>
            </div>
        </fieldset>

        <fieldset class="border rounded-3 p-3 mb-3 card">
            <legend class="float-none w-auto px-3">Detalles:</legend>
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <h5 for="software_id">Software: {{ $software->software_name }} {{ $software->version }}</h5>
                        <input type="hidden" name="software_id" value="{{ $software->id }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="purchase_date">Fecha de Compra:</label>
                        <input type="date" name="purchase_date" class="form-control" required>
                        <small id="purchase-date-error" class="text-danger" style="display: none;"></small>
                        <small id="purchase-date-requirements" class="text-muted">La fecha debe ser como máximo un mes anterior a la fecha actual.</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="expiration_date">Fecha de Expiración:</label>
                        <input type="date" name="expiration_date" class="form-control" required>
                        <small id="expiration-date-error" class="text-danger" style="display: none;"></small>
                        <small id="expiration-date-requirements" class="text-muted">La fecha debe ser al menos un mes después de la fecha de compra.</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Estado:</label>
                        <select name="status" class="form-control" required>
                            <option value="active">Activo</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="border rounded-3 p-3 mb-3 card">
            <legend class="float-none w-auto px-3">Licencias:</legend>
            <h4>Claves de Licencia</h4>
            <div id="license-keys-container">
                <!-- Inputs de claves de licencia se agregarán aquí -->
            </div>

            <button type="button" id="add-license-key" class="btn btn-secondary">Agregar Otra Clave de Licencia</button>
        </fieldset>

        <button type="submit" class="btn btn-primary">Crear Licencias</button>
        <a href="{{ route('licenses.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

</div>

<script>
    document.getElementById('add-license-key').addEventListener('click', function() {
        const container = document.getElementById('license-keys-container');
        const newLicenseKeyEntry = `
            <div class="row g-3 align-items-center mb-2">
                <div class="col-auto">
                    <label>Clave de Licencia:</label>
                </div>
                <div class="col">
                    <input type="text" name="license_key[]" class="form-control" required>
                </div>
                <div class="col-auto pt-3">
                    <button type="button" class="btn btn-primary remove-key">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <small id="license-key-requirements" class="text-muted">La clave debe tener entre 5 y 30 caracteres y no debe contener espacios.</small>

                
            </div>`;
        container.insertAdjacentHTML('beforeend', newLicenseKeyEntry);
    });

    document.getElementById('process-file').addEventListener('click', function() {
        const fileInput = document.getElementById('file');
        const file = fileInput.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                const contents = e.target.result;
                const lines = contents.split('\n');

                const keysContainer = document.getElementById('license-keys-container');
                keysContainer.innerHTML = ''; // Limpiar el contenedor antes de agregar nuevas claves

                lines.forEach(line => {
                    const key = line.trim();
                    if (key) { // Asegurarse de que no esté vacío
                        const newLicenseKeyEntry = `
                            <div class="row g-3 align-items-center mb-2">
                                <div class="col-auto">
                                    <label>Clave de Licencia:</label>
                                </div>
                                <div class="col">
                                    <input type="text" name="license_key[]" class="form-control" value="${key}" required>
                                </div>
                                <div class="col-auto pt-3">
                                    <button type="button" class="btn btn-primary remove-key">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>`;
                        keysContainer.insertAdjacentHTML('beforeend', newLicenseKeyEntry);
                    }
                });
            };

            reader.readAsText(file);
        } else {

            Swal.fire({
                icon: 'warning',
                title: 'Archivo no seleccionado',
                text: 'Por favor selecciona un archivo CSV.',
                confirmButtonText: 'Aceptar'
            });
        }
    });

    // Event delegation for remove buttons
    document.getElementById('license-keys-container').addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-key')) {
            const rowToRemove = event.target.closest('.row');
            if (rowToRemove) {
                rowToRemove.remove(); // Remove the row containing the clicked button
            }
        }
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const purchaseDateInput = document.querySelector('input[name="purchase_date"]');
    const expirationDateInput = document.querySelector('input[name="expiration_date"]');
    const purchaseDateError = document.getElementById('purchase-date-error');
    const expirationDateError = document.getElementById('expiration-date-error');
    const purchaseDateRequirements = document.getElementById('purchase-date-requirements');
    const expirationDateRequirements = document.getElementById('expiration-date-requirements');

    // Validación en tiempo real para la fecha de compra
    purchaseDateInput.addEventListener('input', function() {
        const today = new Date();
        const oneMonthAgo = new Date();
        oneMonthAgo.setMonth(today.getMonth() - 1);
        
        const purchaseDate = new Date(purchaseDateInput.value);
        
        // Reiniciar los mensajes de error
        purchaseDateError.style.display = 'none';
        purchaseDateRequirements.style.display = 'block'; // Mostrar requisitos
        purchaseDateInput.classList.remove('is-invalid');

        // Validar fecha de compra
        if (purchaseDate > oneMonthAgo) {
            purchaseDateError.textContent = 'La fecha de compra debe ser a lo sumo un mes anterior a la fecha actual.';
            purchaseDateError.style.display = 'block';
            purchaseDateInput.classList.add('is-invalid');
        } else {
            purchaseDateInput.classList.add('is-valid');
            purchaseDateRequirements.style.display = 'none'; // Ocultar requisitos si es válido
        }
    });

    // Validación en tiempo real para la fecha de expiración
    expirationDateInput.addEventListener('input', function() {
        const purchaseDate = new Date(purchaseDateInput.value);
        const expirationDate = new Date(expirationDateInput.value);
        
        // Reiniciar los mensajes de error
        expirationDateError.style.display = 'none';
        expirationDateRequirements.style.display = 'block'; // Mostrar requisitos
        expirationDateInput.classList.remove('is-invalid');

        // Validar fecha de expiración
        if (expirationDate <= purchaseDate) {
            expirationDateError.textContent = 'La fecha de expiración debe ser al menos un mes después de la fecha de compra.';
            expirationDateError.style.display = 'block';
            expirationDateInput.classList.add('is-invalid');
        } else {
            expirationDateInput.classList.add('is-valid');
            expirationDateRequirements.style.display = 'none'; // Ocultar requisitos si es válido
        }
    });

    // Validación de claves de licencia en tiempo real
    document.getElementById('license-keys-container').addEventListener('input', function(e) {
        if (e.target.name === 'license_key[]') {
            const keyValue = e.target.value.trim();
            const isValid = keyValue.length >= 5 && keyValue.length <= 30 && !/\s/.test(keyValue);
            
            if (!isValid) {
                e.target.classList.add('is-invalid');
                e.target.classList.remove('is-valid');
            } else {
                e.target.classList.add('is-valid');
                e.target.classList.remove('is-invalid');
            }
        }
    });

   
});
</script>


@endsection