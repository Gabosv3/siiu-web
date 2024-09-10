@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Inventario de Software</h1>
    <a href="{{ route('softwares.create') }}" class="btn btn-primary mb-3">Agregar Software</a>

    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        {{ $message }}
    </div>
    @endif


    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-software-tab" data-bs-toggle="tab" data-bs-target="#nav-software" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Software</button>
            <button class="nav-link" id="nav-desactivados-tab" data-bs-toggle="tab" data-bs-target="#nav-desactivados" type="button" role="tab" aria-controls="nav-desactivados" aria-selected="false">Desactivados</button>

        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-departamentos" role="tabpanel" aria-labelledby="nav-departamentos-tab">
            <div class="shadow-lg p-3 mb-5 bg-body rounded rounded-3 ">
                <table id="Principal" class="table align-items-center mb-0 text-center">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Nombre</th>
                            <th>Versión</th>
                            <th>Fabricante</th>
                            <th>Asignada</th>
                            <th>Ubicación</th>
                            <th>Clasificación de Licencia</th>
                            <th>Tipo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($software as $item)
                        <tr>
                            <td>{{ $item->nombre_software }}</td>
                            <td>{{ $item->version }}</td>
                            <td>{{ $item->fabricante }}</td>
                            <td>{{ $item->asignada }}</td>
                            <td>{{ $item->ubicacion }}</td>
                            <td>{{ $item->clasificacion_licencia }}</td>
                            <td>{{ $item->tipo }}</td>
                            <td>
                                <a href="{{ route('software.show', $item->id) }}" class="btn btn-info">Ver</a>
                                <a href="{{ route('software.edit', $item->id) }}" class="btn btn-primary">Editar</a>
                                <form action="{{ route('software.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('¿Está seguro de eliminar este software?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('components.script-btn')

<script src="{{ asset('assets/js/Tablas/tablas.js') }}"></script>
    @endsection