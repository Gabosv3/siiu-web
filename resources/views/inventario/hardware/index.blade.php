@extends('layouts.user_type.auth')

@section('content')
<div class="row mb-3">
    <div class="col-sm-6">
        {{-- Solo muestra el botón si existe una categoría válida --}}
        @if(request()->has('categoria_id') && request()->input('categoria_id') !== 'all')
            <a href="{{ route('hardwares.create', ['categoria_id' => request()->input('categoria_id')]) }}" class="btn btn-primary">Agregar Hardware</a>
        @endif
    </div>
    <div class="col-sm-6 text-end">
        <form method="GET" action="{{ route('hardwares.index') }}">
            <input type="hidden" name="categoria_id" value="{{ request()->input('categoria_id', 'all') }}">
            <div class="form-group">
                <select name="view" class="form-control" onchange="this.form.submit()">
                    <option value="card" {{ $viewType == 'card' ? 'selected' : '' }}>Card View</option>
                    <option value="table" {{ $viewType == 'table' ? 'selected' : '' }}>Table View</option>
                </select>
            </div>
        </form>
    </div>
</div>

@if($viewType == 'card')
<div class="row">
    @foreach($hardwares as $hardware)
    <div class="col-sm-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $hardware->name }}</h5>
                <p class="card-text">Categoría: {{ $hardware->categoria->nombre }}</p>
                <p class="card-text">Conflictos: {{ $hardware->conflictos }}</p>
                <p class="card-text">Estado: {{ $hardware->estado }}</p>
                <p class="card-text">Dueño: {{ optional($hardware->dueno)->nombre ?? 'No asignado' }}</p>
                <p class="card-text">Ubicación: {{ optional($hardware->ubicacion)->nombre ?? 'No asignado' }}</p>
                <p class="card-text">Código de Inventario: {{ $hardware->codigo_de_inventario }}</p>
                <p class="card-text">Fabricante: {{ optional($hardware->fabricante)->nombre ?? 'N/A' }}</p>
                <p class="card-text">Modelo: {{ optional($hardware->modelo)->nombre ?? 'N/A' }}</p>
                <p class="card-text">Tags:
                    @foreach($hardware->tags as $tag)
                    <span class="badge bg-secondary">{{ $tag->nombre }}</span>
                    @endforeach
                </p>
                <p class="card-text">Sistemas Asignados:
                    @foreach($hardware->sistemasAsignados as $sistema)
                    {{ $sistema->nombre }},
                    @endforeach
                </p>
                <a href="{{ route('hardware.edit', $hardware->id) }}" class="btn btn-primary">Editar</a>
                <form action="{{ route('hardware.destroy', $hardware->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
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
                <thead class="table-primary text-center">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Conflictos</th>
                            <th>Estado</th>
                            <th>Dueño</th>
                            <th>Ubicación</th>
                            <th>Código de Inventario</th>
                            <th>Fabricante</th>
                            <th>Modelo</th>
                            <th>Tags</th>
                            <th>Sistemas Asignados</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                <tbody>
                    @foreach($hardwares as $hardware)
                    <tr>
                        <td>{{ $hardware->name }}</td>
                        <td>{{ $hardware->categoria->nombre }}</td>
                        <td>{{ $hardware->conflictos }}</td>
                        <td>{{ $hardware->estado }}</td>
                        <td>{{ optional($hardware->dueno)->nombre ?? 'No asignado' }}</td>
                        <td>{{ optional($hardware->ubicacion)->nombre ?? 'No asignado' }}</td>
                        <td>{{ $hardware->codigo_de_inventario }}</td>
                        <td>{{ optional($hardware->fabricante)->nombre ?? 'N/A' }}</td>
                        <td>{{ optional($hardware->modelo)->nombre ?? 'N/A' }}</td>
                        <td>
                            @foreach($hardware->tags as $tag)
                            <span class="badge bg-secondary">{{ $tag->nombre }}</span>
                            @endforeach
                        </td>
                        <td>
                            @foreach($hardware->sistemasAsignados as $sistema)
                            {{ $sistema->nombre }},
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('hardwares.edit', $hardware->id) }}" class="btn btn-primary">Editar</a>
                            <form action="{{ route('hardwares.destroy', $hardware->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
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
@endsection
