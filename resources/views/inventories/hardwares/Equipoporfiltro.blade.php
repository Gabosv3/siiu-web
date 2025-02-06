@extends('layouts.user_type.auth')

@section('content')
<div class="container row mb-3">
    <div class="col-sm-12 text-end">
        <a href="{{ route('departaments.index') }}" class="btn btn-secondary">Volver a Departamentos</a>
    </div>
</div>

<div id="hardware-results" style="min-height: 75vh; display: flex; flex-direction: column;">
    <div class="row d-flex justify-content-center">
        <div class="col-sm-6 mb-3">
            <input type="text" id="search" onkeyup="filterItems()" class="form-control" placeholder="Buscar por número de inventario">
        </div>
    </div>
    <div class="row d-flex justify-content-start">
        @foreach($hardwares as $assignment)
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-3 hardware-item">
            <div class="card shadow-sm boxx">
                <div class="card-header text-center d-flex flex-column justify-content-center align-items-center">
                    <div class="card-icon mb-3">
                        @if($assignment->hardware->category && $assignment->hardware->category->image)
                            <img src="{{ asset($assignment->hardware->category->image) }}" alt="{{ $assignment->hardware->category->name }}" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <img src="{{ asset('path/to/default-image.png') }}" alt="Categoría sin imagen" style="width: 100px; height: 100px; object-fit: cover;">
                        @endif
                    </div>
                    <div class="card-title text-truncate" style="max-width: 100%;">
                        <strong>{{ $assignment->hardware->name }}</strong>
                        <h4 class="text-truncate">
                            <a href="{{ route('hardwares.show', $assignment->hardware) }}" class="text-decoration-none">
                                {{ $assignment->hardware->inventory_code }}
                            </a>
                        </h4>
                    </div>
                </div>
                <div class="card-body row text-center">
                    <div class="col-6">
                        <p class="mb-1">Fabricante/Modelo</p>
                        <a href="#" class="text-decoration-none d-inline-block text-truncate" style="max-width: 100%;">
                            {{ optional($assignment->hardware->manufacturer)->name ?? 'N/A' }}/{{ optional($assignment->hardware->model)->name ?? 'N/A' }}
                        </a>
                    </div>
                    <div class="col-6">
                        <p class="mb-1">Estatus</p>
                        <a href="#" class="text-decoration-none d-inline-block text-truncate" style="max-width: 100%;">
                            {{ $assignment->hardware->status }}
                        </a>
                    </div>
                    <div class="col-12 mt-3">
                        @if($assignment->hardware->barcode_path)
                            <img src="{{ asset('storage/' . $assignment->hardware->barcode_path) }}" class="img-fluid" alt="Código de Barras">
                        @else
                            <p class="text-muted">No disponible</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-auto">
        <div class="row d-flex justify-content-between align-items-center mt-3">
            <div class="col-lg-10 sm-12 text-left">
                Mostrando registros del {{ $hardwares->firstItem() }} al {{ $hardwares->lastItem() }} de un total de {{ $hardwares->total() }} registros
            </div>
            @if($hardwares->total() > $hardwares->perPage())
            <div class="col-lg-2 sm-12 ">
                {{ $hardwares->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function filterItems() {
        var input, filter, boxes, boxContainer, title, i;
        input = document.getElementById("search");
        filter = input.value.toUpperCase(); // Convertir a mayúsculas para comparar
        boxContainer = document.getElementById("hardware-results"); // Asegúrate de que este sea el ID correcto
        boxes = boxContainer.getElementsByClassName("hardware-item"); // Cambia a "hardware-item" si es necesario

        // Variable para rastrear si hay un elemento visible
        let firstVisibleElement = null;

        for (i = 0; i < boxes.length; i++) {
            title = boxes[i].querySelector(".card-title");
            if (title && title.innerText.toUpperCase().indexOf(filter) > -1) {
                boxes[i].style.display = ""; // Mostrar el elemento
                if (!firstVisibleElement) {
                    firstVisibleElement = boxes[i]; // Captura el primer elemento visible
                }
            } else {
                boxes[i].style.display = "none"; // Ocultar el elemento
            }
        }

        // Desplazar hacia el primer elemento visible
        if (firstVisibleElement) {
            firstVisibleElement.scrollIntoView({
                behavior: "smooth"
            });
        }
    }
</script>

@endsection
