@extends('layouts.user_type.auth')

@section('content')
<div class="row mb-3">

    <div class="col-sm-4">
        {{-- Solo muestra el botón si existe una categoría válida --}}
        @if(request()->has('category_id') && request()->input('category_id') !== 'all')
        <a href="{{ route('hardwares.create', ['category_id' => request()->input('category_id')]) }}" class="btn btn-primary">Agregar Hardware</a>
        @endif
    </div>
    <div class="col-sm-6">
    </div>
    <div class="col-sm-2 text-end">
        <form method="GET" action="{{ route('hardwares.index') }}">
            <input type="hidden" name="category_id" value="{{ request()->input('category_id', 'all') }}">
            <div class="form-group">
                <select name="view" class="form-control" onchange="this.form.submit()">
                    <option value="card" {{ $viewType == 'card' ? 'selected' : '' }}>Vista Tarjetas</option>
                    <option value="table" {{ $viewType == 'table' ? 'selected' : '' }}>Vista Tablas</option>
                </select>
            </div>
        </form>
    </div>
</div>

@if($viewType == 'card')

<div  id="hardware-results" style=" min-height: 75vh;display: flex; flex-direction: column;">
    <div class="row d-flex justify-content-center">
        <div class="col-sm-6 mb-3">
            <input type="text" id="search" onkeyup="filterItems()" class="form-control" placeholder="Buscar por número de inventario">
        </div>
    </div>
    <div class="row d-flex justify-content-start">
        @foreach($hardwares as $hardware)
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-3 hardware-item">
            <div class="card shadow-sm boxx">
                <div class="card-header text-center d-flex justify-content-around align-items-center">
                    <div class="card-icon">
                        <img src="{{ asset($hardware->category->image) }}" alt="{{ $hardware->category->name }}" style="width: 100px; height: 100px; object-fit: cover">
                    </div>
                    <div class="card-title">{{ $hardware->name }}
                        <h4>{{ $hardware->inventory_code }} </h4>
                    </div>
                </div>
                <div class="card-body row text-center">
                    <div class="col-6">
                        <p>Fabricante/modelo</p>
                        <a href="#" class="text-decoration-none">{{ optional($hardware->manufacturer)->name ?? 'N/A' }}/{{ optional($hardware->model)->name ?? 'N/A' }}</a>
                    </div>
                    <div class="col-6">
                        <p>Estatus</p>
                        <a href="#" class="text-decoration-none">{{ $hardware->status }}</a>
                    </div>
                    <div class="col-12 mt-3">
                        @if($hardware->barcode_path)
                        <img src="{{ asset('storage/' . $hardware->barcode_path) }}" class="img-fluid" alt="Código de Barras">
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

@else
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-software-tab" data-bs-toggle="tab" data-bs-target="#nav-software" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Software</button>
        <button class="nav-link" id="nav-desactivados-tab" data-bs-toggle="tab" data-bs-target="#nav-desactivados" type="button" role="tab" aria-controls="nav-desactivados" aria-selected="false">Desactivados</button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-departamentos" role="tabpanel" aria-labelledby="nav-departamentos-tab">
        <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3">
            <table id="Principal" class="table align-items-center mb-0 text-center">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Código de Inventario</th>
                        <th>Número de Serie</th>
                        <th>Fabricante</th>
                        <th>Modelo</th>
                        <th>Fecha de Expiración de Garantía</th>
                        <th>Código de Barras</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hardwares as $hardware)
                    <tr>
                        <td>{{ $hardware->name }}</td>
                        <td>{{ $hardware->category->name }}</td>
                        <td>{{ $hardware->status }}</td>
                        <td>{{ $hardware->inventory_code }}</td>
                        <td>{{ $hardware->serial_number ?? 'N/A' }}</td>
                        <td>{{ $hardware->manufacturer->name ?? 'N/A' }}</td>
                        <td>{{ $hardware->model->name ?? 'N/A' }}</td>
                        <td>{{ $hardware->warranty_expiration_date ?? 'N/A' }}</td>
                        <td>
                            @if($hardware->barcode_path)
                            <img src="{{ asset('storage/' . $hardware->barcode_path) }}" alt="Código de Barras" style="width: 150px;">
                            @else
                            No disponible
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('hardwares.edit', $hardware->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('hardwares.destroy', $hardware->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este hardware?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@include('components.script-btn')

<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>


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